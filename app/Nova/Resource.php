<?php

namespace App\Nova;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;
use OptimistDigital\NovaDetachedFilters\HasDetachedFilters;
use Laravel\Nova\Fields\Currency;
use App\Models\Province;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Nikaia\Rating\Rating;
abstract class Resource extends NovaResource
{
    use HasDetachedFilters;
    public static $tableStyle = 'tight';
    public static $showColumnBorders = true;
    public static $preventFormAbandonment = true;
    public static $orderBy = [
        'id' => 'desc'
    ];
    public static $combo_rules = null;
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($store_id = $request->user()->store_id) {
            $query->where('store_id', $store_id);
        }

        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            $query->orderBy(key(static::$orderBy), reset(static::$orderBy));
        }
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }

    public function moneyfield($label, $field)
    {
        return Text::make($label, $field)->displayUsing(function($v){return money($v);});
    }

    public function mediaField($label, $collection_name = 'my_multi_collection')
    {
        return Images::make($label, $collection_name) // second parameter is the media collection name
                   // ->conversionOnPreview('original') // conversion used to display the "original" image
                   ->conversionOnDetailView('medium') // conversion used on the model's view
                   ->conversionOnIndexView('thumb') // conversion used to display the image on the model's index page
                   ->conversionOnForm('thumb') // conversion used to display the image on the model's form
                   ->fullSize() // full size column
                   // ->rules('required', 'size:3') // validation rules for the collection of images
                   ->singleImageRules('dimensions:min_width=100');
    }


    public function detachedFilters(Request $request)
    {
        return [];
    }

    // subclass must not have an empty cards()
    public function cards(Request $request)
    {
        return [
            (new NovaDetachedFilters($this->detachedFilters($request)))->withReset()->width('full')
        ];
    }

    public function addressFields()
    {
        return new Panel(__('Address'), [
            Select::make(__('Province'), 'province_id')
                ->options(Province::pluck('name', 'id')->all())
                ->displayUsingLabels()
                ->onlyOnForms(),

            AjaxSelect::make(__('City'), 'city_id')
                ->get('/api/provinces/{province_id}/cities')
                ->parent('province_id')
                ->onlyOnForms(),

            AjaxSelect::make(__('District'), 'district_id')
                ->get('/api/cities/{city_id}/districts')
                ->parent('city_id')
                ->onlyOnForms(),

            Text::make(__('Street'), 'street')->onlyOnForms(),
            Text::make(__('Contact'), 'contact')->onlyOnForms()->nullable(),
            Text::make(__('Mobile'), 'mobile')->onlyOnForms()->nullable(),
            Text::make(__('Address'), 'address')->displayUsing(function(){return $this->display_address();})->onlyOnDetail(),
            Text::make(__('Address'), 'address')->displayUsing(function(){
                $s = $this->display_address();
                if (mb_strlen($s) > 15) {
                    return mb_substr($s, 0, 15) . '...';
                }
                return $s;
            })->onlyOnIndex(),
        ]);
    }

    public function editorField($label, $field)
    {
        return NovaTinyMCE::make($label, $field)->options([
            'height' => '600',
            'language' => 'zh_CN',
            'language_url' => '/tinymce/langs/zh_CN.js'
        ]);
    }

    public function ratingField()
    {
        return Rating::make(__('Rating'), 'rating')//->displayUsing(5)
            ->min(0)
            ->max(5)
            ->increment(0.5)
            ->sortable()
            ->withStyles([
                'star-size' => 20,
                'active-color' => 'var(--danger)', // Primary nova theme color.
                'inactive-color' => '#d8d8d8',
                'border-color' => 'var(--60)',
                'border-width' => 0,
                'padding' => 10,
                'rounded-corners' => false,
                'inline' => false,
                'glow' => 0,
                'glow-color' => '#fff',
                'text-class' => 'inline-block text-80 h-9 pt-2',
            ]);
    }

    public function datetime($label = null, $attr = null)
    {
        return DateTime::make($label ?? __('Created At'), $attr ?? 'created_at');
    }

    public function userName($label = null)
    {
        return Text::make($label ?? __('User'))->displayUsing(function(){return $this->user->name ?? ($this->user->nickname ?? null);})->exceptOnForms();
    }

    public function actionButton($action, $permission, $request)
    {
        return $action
            ->canRun(function ($request, $user) {
                return $request->user()->can($permission, $user);
            })->canRun(function ($request, $user) {
                return $request->user()->can($permission, $user);
            });
    }

    // data: ['perm_name' => (new Actions\xxxxAction)]
    public function actions_with_perm($data, $request)
    {
        $actions = [];
        foreach ($data as $permission => $action) {
            if (!$can = $request->user()->can($permission, \App\Models\User::class)) continue;
            $actions[] = $action->canSee(function () {return true;})->canRun(function ($can) {return true;});
        }
        return $actions;
    }

    public function money($label, $field)
    {
        return Currency::make($label, $field)->currency('CNY');
    }

    /*
     * for example, card_no should be unique in a store
     * comboUniqueValidate($request, ['card_no', 'store_id', 'store']  if you use BlongsTo::make("Store", 'store', Store::class)
     * comboUniqueValidate($request, ['card_no', 'store_id']           if you use Select::make("Store", 'store_id')
     * when use belongsTo components, the form data post to server, has only 'store' instead of 'store_id'
     *
     */
    protected static function comboUniqueValidate(Request $request, $rule)
    {
        $table = (new static::$model)->table;
        if (count($rule) < 2) {
            throw new \Exception("combo unique validator needs at least two fields.");
        }
        $field1 = $rule[0];
        $field2 = $rule[1];
        $field2_obj = $rule[2] ?? $field2;
        $field2_val = $request->post($field2_obj);
        \Log::debug("$field2: ".$field2_val);
        $unique = Rule::unique($table, $field1)->where($field2, $field2_val);
        if ($request->route('resourceId')) {
            $unique->ignore($request->route('resourceId'));
        }
        $uniqueValidator = Validator::make($request->only($field1), [$field1 => [$unique]]);
        return !$uniqueValidator->fails();
    }
    protected static function afterCreationValidation(Request $request, $validator)
    {
        if (static::$combo_rules) {
            foreach (static::$combo_rules as $rule) {
                if (!self::comboUniqueValidate($request, $rule)){
                    $validator->errors()->add($rule[0],$rule[3]);
                }
            }
        }

    }
    protected static function afterUpdateValidation(Request $request, $validator)
    {
        self::afterCreationValidation($request, $validator);
    }
}
