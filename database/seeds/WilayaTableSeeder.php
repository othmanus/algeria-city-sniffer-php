<?php

use Illuminate\Database\Seeder;

use App\Wilaya;

class WilayaTableSeeder extends Seeder {

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('wilayas')->delete();
        DB::update("ALTER TABLE wilayas AUTO_INCREMENT = 1");

        $wilayas = $wilayas = array(
            array('code' => '01','name' => 'Adrar', 'name_ar' => "أدرار"),
            array('code' => '02','name' => 'Chlef', 'name_ar' => " الشلف"),
            array('code' => '03','name' => 'Laghouat', 'name_ar' => "الأغواط"),
            array('code' => '04','name' => 'Oum El Bouaghi', 'name_ar' => "أم البواقي"),
            array('code' => '05','name' => 'Batna', 'name_ar' => "باتنة"),
            array('code' => '06','name' => 'Béjaïa', 'name_ar' => " بجاية"),
            array('code' => '07','name' => 'Biskra', 'name_ar' => "بسكرة"),
            array('code' => '08','name' => 'Béchar', 'name_ar' => "بشار"),
            array('code' => '09','name' => 'Blida', 'name_ar' => "البليدة"),
            array('code' => '10','name' => 'Bouira', 'name_ar' => "البويرة"),
            array('code' => '11','name' => 'Tamanrasset', 'name_ar' => "تمنراست"),
            array('code' => '12','name' => 'Tébessa', 'name_ar' => "تبسة"),
            array('code' => '13','name' => 'Tlemcen', 'name_ar' => "تلمسان"),
            array('code' => '14','name' => 'Tiaret', 'name_ar' => "تيارت"),
            array('code' => '15','name' => 'Tizi Ouzou', 'name_ar' => "تيزي وزو"),
            array('code' => '16','name' => 'Alger', 'name_ar' => "الجزائر"),
            array('code' => '17','name' => 'Djelfa', 'name_ar' => "الجلفة"),
            array('code' => '18','name' => 'Jijel', 'name_ar' => "جيجل"),
            array('code' => '19','name' => 'Sétif', 'name_ar' => "سطيف"),
            array('code' => '20','name' => 'Saïda', 'name_ar' => "سعيدة"),
            array('code' => '21','name' => 'Skikda', 'name_ar' => "سكيكدة"),
            array('code' => '22','name' => 'Sidi Bel Abbès', 'name_ar' => "سيدي بلعباس"),
            array('code' => '23','name' => 'Annaba', 'name_ar' => "عنابة"),
            array('code' => '24','name' => 'Guelma', 'name_ar' => "قالمة"),
            array('code' => '25','name' => 'Constantine', 'name_ar' => "قسنطينة"),
            array('code' => '26','name' => 'Médéa', 'name_ar' => "المدية"),
            array('code' => '27','name' => 'Mostaganem', 'name_ar' => "مستغانم"),
            array('code' => '28','name' => 'M\'Sila', 'name_ar' => "المسيلة"),
            array('code' => '29','name' => 'Mascara', 'name_ar' => "معسكر"),
            array('code' => '30','name' => 'Ouargla', 'name_ar' => "ورقلة"),
            array('code' => '31','name' => 'Oran', 'name_ar' => "وهران"),
            array('code' => '32','name' => 'El Bayadh', 'name_ar' => "البيض"),
            array('code' => '33','name' => 'Illizi', 'name_ar' => "إليزي"),
            array('code' => '34','name' => 'Bordj Bou Arreridj', 'name_ar' => "برج بوعريريج"),
            array('code' => '35','name' => 'Boumerdès', 'name_ar' => "بومرداس"),
            array('code' => '36','name' => 'El Tarf', 'name_ar' => "الطارف"),
            array('code' => '37','name' => 'Tindouf', 'name_ar' => "تندوف"),
            array('code' => '38','name' => 'Tissemsilt', 'name_ar' => "تيسمسيلت"),
            array('code' => '39','name' => 'El Oued', 'name_ar' => "الوادي"),
            array('code' => '40','name' => 'Khenchela', 'name_ar' => "خنشلة"),
            array('code' => '41','name' => 'Souk Ahras', 'name_ar' => "سوق أهراس"),
            array('code' => '42','name' => 'Tipaza', 'name_ar' => "تيبازة"),
            array('code' => '43','name' => 'Mila', 'name_ar' => "ميلة"),
            array('code' => '44','name' => 'Aïn Defla', 'name_ar' => "عين الدفلة"),
            array('code' => '45','name' => 'Naâma', 'name_ar' => "النعامة"),
            array('code' => '46','name' => 'Aïn Témouchent', 'name_ar' => "عين تيموشنت"),
            array('code' => '47','name' => 'Ghardaïa', 'name_ar' => "غرداية"),
            array('code' => '48','name' => 'Relizane', 'name_ar' => "غليزان"),
        );

        foreach($wilayas as $wilaya) {
            Wilaya::create($wilaya);
        }
    }

}
