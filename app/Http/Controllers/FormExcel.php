<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class FormExcel extends Controller
{
    
    public function index()
    {

        // $path = "/home/hossein/Desktop/flarumExcel/resources/neck_comment.csv";
        $path = "/home/rahim/Insert_Excel_To_Flarum_Form/resources/neck_comment.csv";

        $file = fopen($path, 'r');

        if ($file) {


            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);

                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            
            foreach($importData_arr as $importData){
                try{
                    
                    $category = $importData[8]; 
                    if($category == ""){
                        $category = "سایر";
                    }
                }catch(Exception $e){
                    $category = "سایر";
                }
                $this->apiCategory($category);
                
            }
            
            $j = 0;
            foreach ($importData_arr as $importData) {
                // dd($importData[5]);
                $commentTemp = $importData[5]; //Get user names
                $comment = $this->extractComment($commentTemp);
                
                $cat = $importData[8]; 
                if($cat == "سایر"){
                    continue;
                }
            

                
                $this->apiPosting($cat, $comment);
     

                $j++;
            }
            return response()->json([
                'message' => "$j records successfully uploaded"
            ]);
        } else {
            //no file was uploaded
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }

    public function categoryMaker()
    {
            // $path = "/home/hossein/Desktop/flarumExcel/resources/neck_comment.csv";
            $path = "/home/rahim/Insert_Excel_To_Flarum_Form/resources/neck_comment.csv";


            $file = fopen($path, 'r');

            if ($file) {
    
                $importData_arr = array(); // Read through the file and store the contents as an array
                $i = 0;
                //Read the contents of the uploaded file 
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);
    
                    // Skip first row (Remove below comment if you want to skip the first row)
                    if ($i == 0) {
                        $i++;
                        continue;
                    }
                    for ($c = 0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file); //Close after reading
                $j = 0;
                foreach($importData_arr as $importData){
                    try{
    
                        $category = $importData[8]; 
                        if($category == ""){
                            $category = "سایر";
                        }
                    }catch(Exception $e){
                            $category = "سایر";
                    }
                    $this->apiCategory($category);
                    $j++;
                }
                return response()->json([
                    'message' => "$j records successfully uploaded"
                ]);
            } else {
                //no file was uploaded
                throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
            }
    }



    public function generateRandomName($num)
    {
        $items = array(
            'مجید', 'علی', 'مریم', 'الناز', 'محمد', 'عرشیا', 'فاطمه', 'رضا', 'مرتضی', 'محسن', 'حسن', 'علیرضا', 'زهرا', 'مونا', 'طاها',
            'آبان', 'آبتین', 'آبید', 'آتش', 'آتشبان', 'آتشبند', 'آتیلا', 'آدر', 'آذران', 'آذربُد', 'آذرتش', 'آذرداد', 'آذرنگ', 'آرآسب', 'آرا', 'آراد', 'آرام', 'آران', 'آرتاباز', 'آرتان', 'آرتای', 'آرتمان', 'آرتین', 'آرش', 'آرمان', 'آرمون', 'آرمین', 'آرنگ', 'آرون', 'آروین', 'آریا', 'آریاآسب', 'آریابد', 'آریارمنا', 'آریامن', 'آریامنش', 'آریان', 'آریانا', 'آریانو', 'آریوبرزن', 'آزاد', 'آصف', 'آلتون', 'آوگان', 'آوند', 'آیتان', 'آیدین',
            'ابراهیم', 'ابوعلی', 'ابی', 'اپرنگ', 'اتابک', 'اُجای', 'احد', 'احسان', 'احمد', 'اُخشان', 'ادریس', 'ارجاسپ', 'ارجان', 'ارجمند', 'اردا', 'اردشیر', 'اردلان', 'اردوان', 'اردون', 'ارزین', 'ارژن', 'ارژنگ', 'ارستو', 'ارسلان', 'ارسیا', 'ارشاسب', 'ارشام', 'ارشاما', 'ارشان', 'ارشد', 'ارشک', 'ارشمید', 'ارشن', 'ارشیا', 'ارمیا', 'اُرند', 'اروند', 'اسد', 'اسفندیار', 'اسفندیار', 'اسلان', 'اسماعیل', 'اشکان', 'اصغر', 'افراسیاب', 'افرند', 'افرنگ', 'افشار', 'افشین', 'اقبال', 'اکبر', 'اکتای', 'البرز', 'الوند', 'الیا', 'الیاس', 'امجد', 'امید', 'امیر', 'امین', 'انوش', 'انوشیروان', 'اوتانا', 'اوتبر', 'اورنگ', 'اورنگ', 'اُوژن', 'اوشنر', 'ایاز', 'ایراف', 'ایرج', 'ایرمان', 'ایزد', 'ایلا', 'ایمان',
            'بابک', 'باران', 'باربد', 'بارمان', 'بارین', 'بازان', 'بازور', 'بازیار', 'باستام', 'باستین', 'باسیم', 'باشو', 'بامداد', 'بامشاد', 'بامین', 'باور', 'باورد', 'باوند', 'بختیار', 'برجسب', 'برخیا', 'بردیا', 'برزو', 'برزویه', 'برزین', 'برزین', 'برسام', 'برمک', 'برنا', 'برید', 'برین', 'بزرگمهر', 'بشیر', 'بکتاش', 'بلاش', 'بلکا', 'بنان', 'بهادر', 'بهار', 'بهامین', 'بهبود', 'بهپور', 'بهجان', 'بهداد', 'بهدین', 'بهراد', 'بهرام', 'بهرخ', 'بهرنگ', 'بهروز', 'بهزاد', 'بهفر', 'بهکام', 'بهمرد', 'بهمن', 'بهمنیار', 'بهنام', 'بهنیا', 'بهنیود', 'بیژن', 'بینا',
            'پاتون', 'پارسا', 'پاریا', 'پاساد', 'پاشا', 'پاینده', 'پدرام', 'پرتاش', 'پرتام', 'پردیس', 'پرشاد', 'پرشند', 'پرشین', 'پرنگ', 'پرهام', 'پرویز', 'پژمان', 'پُژمان', 'پژواک', 'پشنگ', 'پِشنگ', 'پورنگ', 'پوریا', 'پولاد', 'پویا', 'پویان', 'پیام', 'پیدافر', 'پیران', 'پیروز', 'پیمان', 'پیوند',
            'تابال', 'تاج', 'تاجفر', 'تاجور', 'تراب', 'تلیمان', 'تهماسب', 'تهمتن', 'تهمین', 'توتک', 'تورج', 'تورک', 'تیران', 'تیربُد', 'تیرداد', 'تیرگر', 'تیرنام', 'تیشتار', 'تیمور', 'تینوش',
            'ثابت',
            'جاماسب', 'جامی', 'جاوید', 'جبّار', 'جعفر', 'جلال', 'جلایل', 'جمال', 'جمشید', 'جهان', 'جهانبان', 'جهانبخت', 'جهانبخش', 'جهاندار', 'جهانسوز', 'جهانشاد', 'جهانشاه', 'جهانشیر', 'جهانفر', 'جهانگیر', 'جهانمهر', 'جهانیار', 'جواد', 'جوانشیر', 'جوریل', 'جویان',
            'چاووش', 'چکاد', 'چنگیز',
            'حافظ', 'حامد', 'حامی', 'حانی', 'حبیب', 'حسام', 'حسن', 'حسین', 'حمید', 'حیدر',
            'خرداد', 'خرسند', 'خسرو', 'خشایار',
            'دابا', 'داتیس', 'دادبه', 'دادبین', 'دادفر', 'دادمهر', 'دادور', 'دارا', 'داراب', 'دارمان', 'داریا', 'داریان', 'داریوش', 'دامون', 'دانا', 'دانش', 'دانوش', 'دانیال', 'داور', 'داوود', 'دریا دل', 'دلاور', 'دلیر', 'دولت', 'دیاکو',
            'راجی', 'راد', 'رادبد', 'رادمان', 'رادمنش', 'رادمهر', 'رادین', 'رازان', 'رازبان', 'رازی', 'راستین', 'رامبُد', 'رامتین', 'رامی', 'رامیاد', 'رامیار', 'رامین', 'راهزاد', 'رایان', 'رایکا', 'رحیم', 'رخشان', 'رزین', 'رسام', 'رستم', 'رسول', 'رشید', 'رشین', 'رضا', 'رُکندین', 'رها', 'رهاد', 'رهام', 'رهام', 'روئین', 'روزبه', 'روشاک', 'روشان',
            'زاب', 'زادفر', 'زادمهر', 'زال', 'زامیاد', 'زاهد', 'زاور', 'زراسب', 'زرتشت', 'زروان', 'زروند', 'زریر', 'زکریا', 'زند', 'زواره',
            'ساتیار', 'ساحل', 'سارنگ', 'ساسان', 'ساعد', 'سالار', 'سام', 'سامان', 'سامی', 'سامیار', 'سامین', 'ساویز', 'سپنتا', 'سپنتمان', 'سپند', 'سپندار', 'سپهر', 'ستّار', 'ستوده', 'سردار', 'سرمد', 'سرمند', 'سرواد', 'سروش', 'سریر', 'سعید', 'سلم', 'سلمان', 'سلمک', 'سلیم', 'سلیمان', 'سمراد', 'سمند', 'سمیر', 'سنجر', 'سهراب', 'سهند', 'سهیل', 'سوران', 'سورنا', 'سوشیانت', 'سوفرا', 'سیامک', 'سیاوش', 'سیرمان', 'سیروس', 'سینا', 'سینام', 'سیوا',
            'شاپور', 'شادان', 'شادرخ', 'شادروز', 'شادمهر', 'شادورد', 'شارود', 'شاهد', 'شاهرخ', 'شاهور', 'شاهین', 'شایا', 'شایان', 'شایگان', 'شباویز', 'شبدیز', 'شجاع', 'شروین', 'شریف', 'شمسا', 'شمیل', 'شهاب', 'شهباز', 'شهبال', 'شهبد', 'شهپر', 'شهداد', 'شهراب', 'شهراد', 'شهرام', 'شهرباز', 'شهرداد', 'شهروز', 'شهریار', 'شهکام', 'شهنام', 'شهیار', 'شولان', 'شووان', 'شیداسب', 'شیدفر', 'شیده', 'شیدوش', 'شیرزاد', 'شیروان', 'شیرویه',
            'صابر', 'صادق', 'صبا', 'صدری', 'صدیق', 'صلاح', 'صلاح الدین', 'صمد',
            'ضیا',
            'طاهر', 'طهمورث', 'طوس', 'طوفان',
            'ظفر',
            'عادل', 'عارف', 'عبّاس', 'عدلان', 'عرفان', 'عزیز', 'عطا', 'عظیم', 'علی', 'علی داد', 'عماد', 'عمید', 'عنایت',
            'غدیر',
            'فاتک', 'فاریا', 'فاضل', 'فراز', 'فرازمان', 'فرامرز', 'فرامین', 'فربد', 'فرتاش', 'فرتوس', 'فرج', 'فرجاد', 'فرجام', 'فرّخ', 'فرخاد', 'فرّخزاد', 'فردات', 'فرداد', 'فردوس', 'فردید', 'فردیس', 'فردین', 'فردین', 'فرزاد', 'فرزام', 'فرزان', 'فرزین', 'فرساد', 'فرشاد', 'فرشید', 'فرشیدورد', 'فرشین', 'فرلاس', 'فرناد', 'فرنام', 'فرنود', 'فرهاد', 'فرهان', 'فرهد', 'فرهنگ', 'فرهود', 'فرود', 'فروَد', 'فرورتیش', 'فروردین', 'فروهر', 'فریان', 'فریان', 'فریبرز', 'فرید', 'فریدون', 'فریس', 'فریمان', 'فرینام', 'فریور', 'فیروز', 'فیلک',
            'قادر', 'قاسم', 'قباد', 'قدرت', 'قلندر', 'قیصر',
            'کارن', 'کاظم', 'کام', 'کامبخش', 'کامبد', 'کامبیز', 'کامبین', 'کامدین', 'کامران', 'کامشاد', 'کامکار', 'کاموس', 'کامیار', 'کاوان', 'کاوه', 'کاووس', 'کاویان', 'کتیبه', 'کریم', 'کریمان', 'کریمداد', 'کسرا', 'کشواد', 'کلباد', 'کمال', 'کمبوجیه', 'کواد', 'کورُس', 'کورش', 'کورنگ', 'کوشا', 'کوشان', 'کوشیار', 'کوهیار', 'کیارش', 'کی آرمین', 'کیا', 'کیان', 'کَیان', 'کیانوش', 'کیانوش', 'کیاوش', 'کیخسرو', 'کیقباد', 'کیکاووس', 'کیهان', 'کیوان', 'کیومرث',
            'گرزم', 'گرشاسب', 'گرگین', 'گشتاسب', 'گودرز', 'گورنگ', 'گوشاسب', 'گوماتا', 'گیو',
            'لسان', 'لهراسب', 'لیث',
            'مازار', 'مازیار', 'ماکان', 'مانک', 'مانوش', 'مانی', 'ماهان', 'ماهر', 'متین', 'مجتبی', 'مجید', 'محسن', 'محمّد', 'محمود', 'مراد', 'مرتضی', 'مرتیا', 'مردآویج', 'مرداس', 'مرزبان', 'مروان', 'مزدا', 'مزدک', 'مسعود', 'مصطفی', 'معین', 'مقصود', 'مکابیز', 'مَلِک', 'ملیک', 'منصور', 'منوچهر', 'مهبد', 'مهداد', 'مهدی', 'مهرا', 'مهراب', 'مهراد', 'مهراشک', 'مهرام', 'مهرام', 'مهران', 'مهربان', 'مهرتاش', 'مهرداد', 'مهرزاد', 'مهرساد', 'مهرشاد', 'مهرک', 'مهرگان', 'مهرنام', 'مهرنگ', 'مهرنوش', 'مهرورز', 'مهروند', 'مهریار', 'مهریار', 'مهوار', 'مهوند', 'مهیار', 'مهیاز', 'مهیمن', 'موسی', 'میثاق', 'میثم', 'میرزا', 'میعاد', 'میلاد',
            'نادر', 'ناصر', 'نامدار', 'نامور', 'نامی', 'نجید', 'نرسی', 'نریمان', 'نَستور', 'نشواد', 'نصرت', 'نصیح', 'نصیر', 'نظام', 'نعمت', 'نوبان', 'نوروز', 'نوری', 'نوزر', 'نوژان', 'نوشزاد', 'نوشیروان', 'نوند', 'نویان', 'نوید', 'نوین', 'نیرَم', 'نیرو', 'نیک', 'نیک آهنگ', 'نیکا', 'نیکان', 'نیکاو', 'نیکروز', 'نیکزاد', 'نیکنام', 'نیکنیا', 'نیما', 'نیماد', 'نیناد', 'نیو', 'نیواد', 'نیوتور', 'نیوراد', 'نیوزاد', 'نیوشا', 'نیوند',
            'هاتف', 'هادی', 'هارون', 'هاشم', 'هامان', 'هامون', 'هدایت', 'هرمز', 'هژیر', 'هشام', 'همایون', 'هوتن', 'هورداد', 'هوشان', 'هوشمند', 'هوشنگ', 'هوشیار', 'هومان', 'هومن', 'هومین', 'هونام', 'هیتاسب', 'هیراد', 'هیربد', 'هیرسا', 'هیرمند', 'هیوند',
            'وارتان', 'واروژ', 'واریان', 'والا', 'واله', 'وجیح', 'وحدت', 'وحید', 'ورجاوند', 'ورشاسب', 'ورفان', 'ورنا', 'وُریا', 'وشمگیر', 'وفا', 'ونداد', 'وهاب', 'وهبد', 'وهرز', 'ویراف', 'ویسه', 'ویشپر', 'ویشتاسب',
            'یاران', 'یاری', 'یازان', 'یاشار', 'یامین', 'یاور', 'یاوند', 'یحیی', 'یزدان', 'یزدان بخش', 'یزدانفر', 'یزدگرد', 'یعقوب', 'یوسف', 'یونس',

            'آبان', 'آبان بانو', 'آبان دخت', 'آتاناز', 'آتسا', 'آتنا', 'آتنه', 'آتوسا', 'آتوشه', 'آتیشه', 'آذر', 'آذرافروز', 'آذران', 'آذرجهر', 'آذرخش', 'آذرداد', 'آذردخت', 'آذرشین', 'آذرفروز', 'آذرک', 'آذرگل', 'آذرگون', 'آذرمهر', 'آذرمینا', 'آذرنوش', 'آذروان', 'آذریاس', 'آذرین', 'آذین', 'آذین بانو', 'آذین دخت', 'آرا', 'آراسته', 'آرام بانو', 'آرایه', 'آرتا', 'آرتادخت', 'آرتمیس', 'آرتنوس', 'آرزو', 'آرسته', 'آرمان', 'آرمیتا', 'آرمیلا', 'آرمین دخت', 'آروشا', 'آریا', 'آریان', 'آریانا', 'آرین', 'آرین', 'آزاد دخت', 'آزادمهر', 'آزاده', 'آزرمیدخت', 'آزیتا', 'آژند', 'آسا', 'آسام', 'آسمان', 'آسمانه', 'آسیا', 'آسیه', 'آصفه', 'آفاق', 'آفتاب', 'آفری', 'آفرین', 'آلاله', 'آلما', 'آلیش', 'آمنه', 'آموتیا', 'آمیتریس', 'آمیتریس', 'آمیتیس', 'آنا', 'آناهیتا', 'آندیا', 'آنوش', 'آنیتا', 'آهنگ', 'آهو', 'آوا', 'آوازه', 'آوند', 'آوید', 'آویده', 'آویز', 'آویزه', 'آویژه', 'آویسا', 'آویش', 'آویشن', 'آویشه', 'آوین', 'آیتان', 'آیدا', 'آیدان', 'آیسا', 'آیسان', 'آیسان', 'آیسل', 'آیلا', 'آیلین', 'آیناز', 'آیه', 'ابریشم', 'احترام', 'اختر', 'ارانوس', 'ارزین', 'ارستو', 'ارغوان', 'ارکیده', 'ارم', 'ارمغان', 'ارنواز', 'اروانه', 'اروسا', 'اریکا', 'اِستاتیرا', 'استر', 'اشرف', 'اعظم', 'افتخار', 'افرا', 'افرند', 'افروز', 'افروزه', 'افری', 'افسان', 'افسانه', 'افسر', 'افسون', 'افشان', 'افشانه', 'افشک', 'افشنگ', 'افشید', 'افشیده', 'افشینه', 'اکرم', 'الدوز', 'الفت', 'الماس', 'المیرا', 'الناز', 'الهام', 'الهه', 'الیا', 'الیزه', 'الیکا', 'امیتیس', 'امید', 'امیده', 'امیربانو', 'امیره', 'انارام', 'اندیشه', 'انسی', 'انسیه', 'انور', 'انوشا', 'انوشک', 'انوشه', 'انیس', 'انیسا', 'انیسه', 'اورسیا', 'اوزن', 'اولیا', 'ایده', 'ایران', 'ایران بانو', 'ایران دخت', 'ایرسا', 'ایرسیا', 'ایلا', 'ایمان',
            'بابوک', 'باران', 'بارانک', 'بارانه', 'بامی', 'بامیک', 'بامین', 'باناز', 'بانو', 'بانویه', 'بدری', 'بدریه', 'برسابه', 'برسین', 'برسینا', 'بلور', 'بنفشه', 'به آذر', 'به آذین', 'به آرا', 'به آفرید', 'به آفرین', 'به بها', 'بها', 'بهار', 'بهاربانو', 'بهارک', 'بهاره', 'بهاک', 'بهامین', 'بهتام', 'بهتن', 'بهجان', 'بهجت', 'بهدخت', 'بهدله', 'بهدیس', 'بهرامن', 'بهرخ', 'بهرو', 'بهشته', 'بهشید', 'بهمیس', 'بهناز', 'بهناک', 'بهنوش', 'بههن', 'بهی', 'بهین', 'بهینه', 'بوبک', 'بوران', 'بوران دخت', 'بوسه', 'بی بی', 'بی بی ناز', 'بیتا', 'بیدخت',
            'پاپلی', 'پاتونه', 'پارسادخت', 'پارمیس', 'پارمین', 'پارنیز', 'پاکسیما', 'پاکنوش', 'پالیز', 'پانته آ', 'پانویه', 'پانیذ', 'پدیده', 'پرتو', 'پرخیده', 'پردیس', 'پرستان', 'پرسته', 'پرستو', 'پرستوک', 'پرسون', 'پرشاد', 'پرشنگ', 'پرشه', 'پرگل', 'پرمون', 'پرمیدا', 'پرنا', 'پرناز', 'پرند', 'پرندیس', 'پرندین', 'پرنگ', 'پرنو', 'پرنیا', 'پرنیان', 'پروا', 'پروان', 'پروانه', 'پروچیستا', 'پرور', 'پروسکا', 'پروشات', 'پروند', 'پروه', 'پروین', 'پری', 'پری دخت', 'پری رو', 'پریا', 'پریجهان', 'پریچهر', 'پریچهره', 'پریزاد', 'پریسا', 'پریساتیس', 'پریسان', 'پریسوز', 'پریسیما', 'پریشاد', 'پریشم', 'پریما', 'پرین', 'پریناز', 'پریناز', 'پرینوش', 'پریور', 'پریوش', 'پژواک', 'پسند', 'پگاه', 'پلاگه', 'پوپک', 'پوپه', 'پوران', 'پوران دخت', 'پورکار', 'پوری', 'پونل', 'پونه', 'پیچک', 'پیراسته', 'پیرایه', 'پیروزدخت', 'پیروزه', 'پیمانه', 'پیموده', 'پیوند',
            'تابا', 'تابان', 'تابش', 'تاج بانو', 'تارا', 'تانیا', 'تبسم', 'ترانه', 'ترسا', 'ترمه', 'ترنگ', 'تکتم', 'تندیس', 'تهمینه', 'توتک', 'توران', 'توران خت', 'توریا', 'توسکا', 'توکا', 'تیبا', 'تیرا', 'تیراژه', 'تیرام', 'تیکا', 'تینا',
            'ثریّا', 'ثمر', 'ثمره', 'ثمن', 'ثمیلا', 'ثمینا',
            'جامک', 'جانان', 'جانفروز', 'جاودانه', 'جبّاره', 'جریره', 'جلا', 'جلوه', 'جلیله', 'جمیله', 'جنّت', 'جهان', 'جهان آرا', 'جهان بانو', 'جهان تاب', 'جهان دخت', 'جهان ناز', 'جوانه', 'جیران',
            'چامه', 'چشمک', 'چکا', 'چکامه', 'چکاوک', 'چلیپا', 'چمان', 'چمانه', 'چمن', 'چیترا', 'چیستا', 'چیکا',
            'حاتفه', 'حامده', 'حامی', 'حامیه', 'حبیبه', 'حدا', 'حدیث', 'حدیقه', 'حرمت', 'حریره', 'حسنا', 'حلیمه', 'حمیده', 'حمیرا', 'حنا', 'حوّا', 'حور', 'حورا', 'حوروش', 'حوری', 'حوریه',
            'خاتون', 'خاطره', 'خاور', 'خاوردخت', 'خجسته', 'خدیجه', 'خزر', 'خندان', 'خنده', 'خنیا', 'خورشاد', 'خورشید', 'خورشید بانو', 'خوروش', 'خوشه', 'خینا',
            'دامینه', 'دانا', 'دانه', 'داور', 'دردانه', 'درسا', 'درنا', 'درناز', 'دری', 'دریا', 'دریاناز', 'دریانه', 'دل آرا', 'دل آسا', 'دل آویز', 'دل افرز', 'دلارام', 'دلبر', 'دلبند', 'دلربا', 'دلشاد', 'دلکش', 'دلناز', 'دلنواز', 'دلیار', 'دلیله', 'دنا', 'دنیا', 'دنیازاد', 'دنیاناز', 'دیبا', 'دینا', 'دینک', 'دینه',
            'راحله', 'راحیل', 'رادنوش', 'راز', 'رازان', 'رازک', 'راستا', 'راستاک', 'راستینه', 'راسن', 'راشین', 'راضیه', 'راضیه', 'راما', 'رامبهشت', 'رامدخت', 'رامش', 'رامک', 'رامونا', 'رامین دخت', 'رامینا', 'رامینه', 'راهله', 'راوک', 'رایکا', 'رباب', 'ربابه', 'رجاء', 'رخسارا', 'رخساره', 'رخشا', 'رخشاد', 'رخشاد', 'رخشان', 'رخشان', 'رخند', 'رُدگون', 'ردیمه', 'رزما', 'رسا', 'رشا', 'رشاد', 'رشیا', 'رضوان', 'رعنا', 'رفا', 'رقیّه', 'رکسانا', 'رها', 'رهادخت', 'روا', 'روان', 'روجا', 'روح انگیز', 'رودابه', 'روزچهر', 'روژان', 'روژین', 'روژینا', 'روشانه', 'روشن', 'روشنا', 'روشنک', 'رومینا', 'روناک', 'رونق', 'رویا', 'رویا', 'ریتا', 'ریحان', 'ریحانک', 'ریحانه', 'ریکا', 'ریما', 'ریماز',
            'زرآسا', 'زرافشان', 'زربانو', 'زرسا', 'زری', 'زریله', 'زرّین', 'زرّین تاج', 'زرّین دخت', 'زرّینه', 'زنبق', 'زها', 'زهرا', 'زهره', 'زوزان', 'زویا', 'زیبا', 'زیباچهر', 'زیبادخت', 'زیکا', 'زینا', 'زینب', 'زینت', 'زیور',
            'ژاله', 'ژامک', 'ژاوه', 'ژیلا', 'ژیله', 'ژینا',
            'ساحل', 'سارا', 'سارنگ', 'ساره', 'سارینا', 'ساغر', 'ساقی', 'سالومه', 'سامه', 'سامیه', 'ساناز', 'سانوا', 'ساویس', 'ساینا', 'سایه', 'سپنتا', 'سپهرم', 'سپیدا', 'سپیده', 'سپینود', 'ستاره', 'ستّاره', 'ستاه', 'ستوده', 'سحر', 'سحرناز', 'سرمه', 'سرور', 'سرور', 'سروشه', 'سروناز', 'سروند', 'سروین', 'سزانه', 'سعیده', 'سلا', 'سلامه', 'سلما', 'سلماز', 'سلمه', 'سلیمه', 'سماء', 'سمانه', 'سمراد', 'سمن', 'سمناز', 'سمنبر', 'سمنه', 'سمیرا', 'سمیره', 'سمینه', 'سمیّه', 'سنبل', 'سها', 'سهی', 'سهیلا', 'سودابه', 'سورا', 'سوران', 'سوری', 'سوزان', 'سوسن', 'سوسنک', 'سوگل', 'سوگلی', 'سوگند', 'سیتا', 'سیتا', 'سیرادخت', 'سیرانوش', 'سیکا', 'سیما', 'سیماه', 'سیمبر', 'سیمتن', 'سیمیا', 'سیمین', 'سیمین دخت', 'سیندخت', 'سیوا',
            'شاپرک', 'شادآفرین', 'شادان', 'شادمان', 'شادناز', 'شاده', 'شادی', 'شالیزه', 'شاندیز', 'شاهپری', 'شاهد', 'شاهزاده', 'شاهگل', 'شاهنگ', 'شاهوش', 'شاورد', 'شاوه', 'شایا', 'شایان دخت', 'شایسته', 'شباهنگ', 'شبپر', 'شبناز', 'شبنم', 'شده', 'شراره', 'شرمین', 'شروین', 'شریفه', 'شعله', 'شفق', 'شقایق', 'شکرانه', 'شکوفه', 'شکوه', 'شکیبا', 'شمس', 'شمسی', 'شمیا', 'شمیسا', 'شمیلا', 'شمیم', 'شمین', 'شهپر', 'شهرا', 'شهرزاد', 'شهرناز', 'شهرناو', 'شهرنواز', 'شهرنوش', 'شهره', 'شهرود', 'شهلا', 'شهناز', 'شهنواز', 'شهین', 'شورانگیز', 'شورت', 'شوشا', 'شوکا', 'شوکت', 'شیبا', 'شیدا', 'شیدخت', 'شیدرخ', 'شیده', 'شیدوش', 'شیراز', 'شیردخت', 'شیرین', 'شیرین بانو', 'شیفته', 'شیما', 'شیوا', 'شیواد', 'شیوه',
            'صابره', 'صبا', 'صدا', 'صدف', 'صدیقه', 'صفا', 'صفورا', 'صفیّه', 'صنم', 'صنوبر', 'صهبا',
            'طاهره', 'طاوس', 'طراوت', 'طلا', 'طلایه', 'طلعت', 'طناز', 'طوبی', 'طوسک', 'طوفان', 'طیبه',
            'ظریف', 'ظریفه',
            'عادله', 'عادیله', 'عاطفه', 'عالیه', 'عبّاسه', 'عدیله', 'عذرا', 'عزیز', 'عزیزه', 'عسل', 'عصمت', 'عطیفه', 'عفت', 'عقدس', 'عقیق', 'عنبر', 'عهدیه',
            'غزال', 'غزاله', 'غزل', 'غمزه', 'غنچه',
            'فائزه', 'فائقه', 'فاخته', 'فاخره', 'فاریا', 'فاطمه', 'فتّانه', 'فتنه', 'فخری', 'فرانک', 'فرانه', 'فرح', 'فرحناز', 'فرخ رو', 'فرخروز', 'فرخنده', 'فردخت', 'فردوس', 'فرزانک', 'فرزانه', 'فرشته', 'فرشیده', 'فرمهر', 'فرناز', 'فرنگ', 'فرنگیس', 'فرنوش', 'فرنیا', 'فروز', 'فروزا', 'فروزان', 'فروزنده', 'فروغ', 'فریا', 'فریال', 'فریبا', 'فریدا', 'فریده', 'فریفته', 'فریما', 'فریماه', 'فریمهر', 'فرین', 'فریّن', 'فریّن چهر', 'فریناز', 'فرینام', 'فرینوش', 'فقیهه', 'فلامک', 'فلورا', 'فهیمه', 'فوژان', 'فیروزه', 'فیلا',
            'قاسدک', 'قدسی', 'قشنگ', 'قمر',
            'کاژیره', 'کاساندان', 'کاملیا', 'کامینه', 'کاناز', 'کبری', 'کتانه', 'کتایون', 'کترا', 'کرشمه', 'کژال', 'کلاله', 'کمند', 'کوکب', 'کیان دخت', 'کیانا', 'کیدرا', 'کیمیا', 'کیهان', 'کیهان بانو',
            'گردآفرید', 'گردیا', 'گل آذین', 'گل افشار', 'گل افشان', 'گل اندام', 'گل نسرین', 'گلاب', 'گلاره', 'گلاره', 'گلاره', 'گلاویز', 'گلاویژ', 'گلایل', 'گلبان', 'گلبانو', 'گلبرگ', 'گلبهار', 'گلبو', 'گلپر', 'گلپری', 'گلچهر', 'گلچهره', 'گلدار', 'گلدوز', 'گلرخ', 'گلرنگ', 'گلرو', 'گلریز', 'گلسا', 'گلسان', 'گلشاد', 'گلشن', 'گلشنک', 'گلشهر', 'گلشید', 'گلشیفته', 'گلک', 'گلگون', 'گلمهر', 'گلنار', 'گلناز', 'گلنام', 'گلنسا', 'گلنواز', 'گلنوش', 'گلی', 'گوهر', 'گوهرشاد', 'گیتا', 'گیتی', 'گیسو', 'گیسی', 'گیلدا',
            'لادن', 'لاله', 'لبینا', 'لطیفه', 'لُعبت', 'لعیا', 'لوما', 'لیان', 'لیدا', 'لیلا', 'لیلا', 'لیلاس', 'لیلوپر', 'لیلی', 'لیلی', 'لیلیا', 'لیما', 'لینا',
            'مائده', 'مارال', 'ماریه', 'مامک', 'مامیسا', 'مانا', 'ماندا', 'ماندیس', 'مانلی', 'مانی', 'مانیا', 'ماه آفرد', 'ماهدخت', 'ماهرخ', 'ماهرخسارر', 'ماهرو', 'محبوبه', 'محیا', 'مدینه', 'مراجل', 'مرجان', 'مرجانه', 'مرسده', 'مرضیه', 'مرمر', 'مروارید', 'مریم', 'مژده', 'مژگان', 'مستانه', 'مستوره', 'مشیا', 'معصومه', 'ملاحت', 'ملکه', 'ملکه جهان', 'ملود', 'ملودی', 'ملوس', 'ملوک', 'ملیح', 'ملیحه', 'ملیسا', 'ملیکا', 'منصوره', 'منظر', 'منوّر', 'منیر', 'منیره', 'منیژه', 'منیلا', 'مه سیما', 'مه منیر', 'مها', 'مهان', 'مهبانو', 'مهتا', 'مهتاب', 'مهتاج', 'مهجبین', 'مهداد', 'مهدخت', 'مهدیس', 'مهدیه', 'مهرآرا', 'مهرآسا', 'مهرآفرین', 'مهرا', 'مهراز', 'مهران', 'مهراندخت', 'مهرانگیز', 'مهرانه', 'مهراور', 'مهراوه', 'مهربانو', 'مهرخ', 'مهردخت', 'مهرزاد', 'مهرسا', 'مهرشید', 'مهرک', 'مهرناز', 'مهرنوش', 'مهرورز', 'مهروش', 'مهری', 'مهزاد', 'مهسا', 'مهسان', 'مهستی', 'مهسو', 'مهشاد', 'مهشید', 'مهفام', 'مهکامه', 'مهلا', 'مهلقا', 'مهناز', 'مهنوش', 'مهوش', 'مهین', 'مهین بانو', 'مهیندخت', 'موجان', 'موژان', 'مونا', 'مونا', 'میترا', 'میثاق', 'میثمه', 'میچکا', 'میشا', 'میلا', 'میلی', 'مینا', 'مینو', 'میهن', 'میهن یار',
            'ناجی', 'نادره', 'نادی', 'نادیا', 'نارسیس', 'نارین', 'ناز', 'نازآفرین', 'نازبانو', 'نازبو', 'نازپری', 'نازتا', 'نازدانه', 'نازدخت', 'نازک', 'نازگل', 'نازلی', 'نازنوش', 'نازنین', 'نازو', 'نازی', 'نازیتا', 'نازیلا', 'نازینه', 'ناژو', 'ناژین', 'ناناز', 'ناهید', 'ناهیده', 'نجلا', 'نجمه', 'نجوی', 'ندا', 'نرجس', 'نرگس', 'نرمین', 'نرمینه', 'نزهت', 'نسا', 'نسترن', 'نسرین', 'نسیم', 'نشاط', 'نشوا', 'نشید', 'نظیره', 'نغمه', 'نفیسه', 'نکیسا', 'نگار', 'نگارین', 'نگان', 'نگاه', 'نگین', 'نهال', 'نهاله', 'نوا', 'نواز', 'نوال', 'نوبر', 'نور', 'نورا', 'نورانگیز', 'نوژان', 'نوشآفرین', 'نوشا', 'نوشابه', 'نوشبر', 'نوشه', 'نوشین', 'نوشینه', 'نوگل', 'نویده', 'نویسه', 'نوین', 'نیاز', 'نیاز', 'نیّر', 'نیرا', 'نیّره', 'نیسا', 'نیسیا', 'نیکا', 'نیکپر', 'نیکتا', 'نیکدخت', 'نیکدل', 'نیکناز', 'نیکی', 'نیکی', 'نیکی ناز', 'نیلگون', 'نیلوفر', 'نینا', 'نیوشا', 'نیوشه',
            'هاله', 'هانا', 'هاني', 'هانيه', 'هایده', 'هدیه', 'هستی', 'هلاله', 'هما', 'همای', 'همراز', 'هنگامه', 'هوردخت', 'هورشید', 'هیلا', 'هیلا', 'هیلدا', 'هیوا',
            'وارسته', 'واژه', 'واله', 'وانوشه', 'وجستا', 'وجیهه', 'ورتا', 'ورد', 'وردا', 'ورسا', 'ورنا', 'وستا', 'وشتا', 'وشتی', 'وصال', 'وندا', 'ونیژه', 'ویدا', 'ویرا', 'ویره', 'ویژه', 'ویس', 'ویستا', 'ویشکا', 'وینا',
            'یارا', 'یاس', 'یاسمن', 'یاسمین', 'یاقوت', 'یزدانه', 'یکامه', 'یکتا', 'یگانه', 'یلدا', 'یوتاب'
        
        );

        // $num = array_rand($items, 1);
        return $items[$num];
    }

    public function extractComment($text)
    {
        $comment="";
        if (str_contains($text, '@')) {

            $comment = substr(strstr($text, " "), 1);
            
        }else{
            $comment = $text;
        }
        return "<t><p>".$comment."</p></t>";
    }

    public function userreg()
    {
        $count =0;
        $total =200;

        for($count;$count<$total;$count++){
            $randd=rand(0,1700);
            $name = $this->generateRandomName($randd);
            $email = $this->generateRandomEmailAddress();
            $password = $this->generateRandomPassword();
            $sql = "INSERT INTO users (username ,email ,password)
            VALUES ('$name' , '$email' , '$password')";

            $resultI = DB::insert($sql, [1]);
        }


    }

    // public function apiRegistration()
    // {
    //     // $name = $this->generateRandomName();
    //     $num = rand(1,400);

    //     $email = $this->generateRandomEmailAddress();
    //     $password = $this->generateRandomPassword();
    //     // dd($name,$email,$password);
    //     // $sql = "SELECT glc.*
    //     //         FROM geoip_blocks gbl
    //     //         JOIN geoip_locations glc ON glc.glc_id = gbl.gbl_glc_id
    //     //         WHERE gbl_block_start <= INET_ATON('149.156.1.4')
    //     //         ORDER BY gbl_block_start DESC
    //     //         LIMIT 1";  

    //     $sql = "INSERT INTO users (username ,email ,password)
    //             VALUES ('$name' , '$email' , '$password')";

    //     $resultI = DB::insert($sql, [1]);

    //     $sqlUserId = "SELECT id
    //                 FROM users
    //                 WHERE username = '$name'";
    //     $resultU = DB::select($sqlUserId, [1])[0];
    //     return $resultU;
    // }

    public function apiPosting($category, $content)
    {

        // $userId = $this->apiRegistration();

        $sqlgetuserid = "SELECT id 
                        FROM users";
        $userids = DB::select($sqlgetuserid,[1]);
        // dd($userids);
        $random = array_rand($userids,1);
        $userId = $userids[$random]; 
        // dd($userId);
        $categorysql = "SELECT id
                    FROM discussions
                    WHERE title = '$category'";

        $resultCategory = DB::select($categorysql, [1])[0];
        // dd($resultCategory,$content,$userId);
        $now =now();

        $sql = "INSERT INTO posts (discussion_id ,content ,user_id ,created_at, type)
                VALUEs ('$resultCategory->id' ,'$content' ,'$userId->id', '$now' ,'comment')";

        $resultI = DB::insert($sql, [1]);
       
    }

    public function apiCategory($category)
    {
        $now=now();
        $sql="SELECT title
            FROM discussions";

        $temp1 = DB::select($sql,[1]);
        // dd($temp1->title);
        foreach ($temp1 as $t){
            // dd($t->title);
            if($t->title == $category)
            return;
        };


        $sql2 ="INSERT IGNORE INTO discussions (title ,created_at ,user_id)
                VALUES ('$category', '$now' ,1)";
                




        $temp = DB::insert($sql2,[1]);
     
    }

    function generateRandomPassword()
    {

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function generateRandomEmailAddress()
    {

        $maxLenLocal = 20;
        $maxLenDomain = 12;
        $numeric        =  '0123456789';
        $alphabetic     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $extras         = '.-_';
        $all            = $numeric . $alphabetic . $extras;
        $alphaNumeric   = $alphabetic . $numeric;
        $alphaNumericP  = $alphabetic . $numeric . "-";
        $randomString   = '';

        // GENERATE 1ST 4 CHARACTERS OF THE LOCAL-PART
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $alphabetic[rand(0, strlen($alphabetic) - 1)];
        }
        // GENERATE A NUMBER BETWEEN 20 & 60
        $rndNum         = rand(10, $maxLenLocal - 4);

        for ($i = 0; $i < $rndNum; $i++) {
            $randomString .= $all[rand(0, strlen($all) - 1)];
        }

        // ADD AN @ SYMBOL...
        $randomString .= "@";

        // GENERATE DOMAIN NAME - INITIAL 3 CHARS:
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $alphabetic[rand(0, strlen($alphabetic) - 1)];
        }

        // GENERATE A NUMBER BETWEEN 15 & $maxLenDomain-7
        $rndNum2        = rand(15, $maxLenDomain - 7);
        for ($i = 0; $i < $rndNum2; $i++) {
            $randomString .= $all[rand(0, strlen($all) - 1)];
        }
        // ADD AN DOT . SYMBOL...
        $randomString .= ".";

        // GENERATE TLD: 4
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $alphaNumeric[rand(0, strlen($alphaNumeric) - 1)];
        }

        return $randomString;
    }
}
