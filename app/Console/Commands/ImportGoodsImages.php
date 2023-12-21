<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use App\Models\Goods;

class ImportGoodsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:goods-images {collection} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "for example: import:goods-images main ./images.csv\nor import:goods-images detail ./images.csv";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $goods = Goods::find(712);
        // $goods->addMedia()->toMediaCollection("main");
        $collection = $this->argument("collection");
        $path = $this->argument("path");

        if (!$fp = fopen($path, "r")) {
            echo "CANNOT open $path\n";
            exit(1);
        }
        while ($row = fgetcsv($fp, 10000, ",")) {
            if (count($row) < 2) {
                echo "data error: ".$row[0];
                continue;
            }
            $id = $row[0];
            $imgs = explode(',', $row[1]);
            echo "add images to goods $id\n";
            foreach ($imgs as $imgpath) {
                $imgpath = "./public".$imgpath;
                echo "  $imgpath ";
                if (file_exists($imgpath)) {
                    Goods::find($id)->addMedia($imgpath)->toMediaCollection($collection);
                    echo " ADDED\n";
                }else{
                    echo " not exists!\n";
                }
            }
        }

        return 0;
    }

    /**
     * Create an UploadedFile object from absolute path
     *
     * @param     string $path
     * @param     bool $test default true
     * @return    object(Illuminate\Http\UploadedFile)
     *
     * Based of Alexandre Thebaldi answer here:
     * https://stackoverflow.com/a/32258317/6411540
     */
    public function pathToUploadedFile( $path, $test = true ) {
        $filesystem = new Filesystem;

        $name = $filesystem->name( $path );
        $extension = $filesystem->extension( $path );
        $originalName = $name . '.' . $extension;
        $mimeType = $filesystem->mimeType( $path );
        $error = null;

        return new UploadedFile( $path, $originalName, $mimeType, $error, $test );
    }
}
