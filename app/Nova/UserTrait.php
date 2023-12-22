<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Vyuldashev\NovaPermission\RoleSelect;

trait UserTrait{
    public function title()
    {
        return ($this->name ?? $this->nickname) . $this->mobile;
    }
    public function userFields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Store'),'store', Store::class)->nullable(),
            Image::make(__('Avatar'), 'avatar')->maxWidth(50)->preview(function($val){return $val;})->thumbnail(function($val){return $val;}),
            Text::make(__('Nickname'), 'nickname'),
            Text::make(__('Realname'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Province'), 'province'),
            Text::make(__('City'), 'city'),
            Text::make(__('Gender'), 'gender'),
            Text::make(__('Mobile'), 'mobile'),
            $this->moneyfield(__('Balance'), 'balance'),
            $this->moneyfield(__('Withdraw Quota'), 'quota'),
            Date::make(__("Rewards Expires"), "rewards_expires_at"),
            BelongsTo::make(__('Referer'), 'referer', User::class),
            Select::make(__("Status"), 'status')->options(function(){return \App\Models\User::statusOptions();})->displayUsingLabels(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            $this->mediaField(__('ID'), 'id card'),
        ];
    }
}
