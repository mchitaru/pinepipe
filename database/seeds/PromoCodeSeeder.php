<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\PromoCode;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                
        $codes = array();

        for($idx = 0; $idx < 10000; $idx++){        

            do{

                $code = Str::random(6);
                
            }while(array_search($code, $codes, true) != FALSE);

            $codes[] = $code;
        }

        foreach($codes as $code){

            $promoCode = PromoCode::create(['code' => $code]);
        }
    }
}
