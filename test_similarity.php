<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$message = "hai"; // Contoh pesan user
$faqs = App\Models\Faq::where('is_aktif', true)->get();

$bestMatch = null;
$bestSimilarity = 0;

foreach ($faqs as $faq) {
    $similarity = 0;
    similar_text(strtolower($message), strtolower($faq->pertanyaan), $similarity);
    echo "Pesan: '$message' vs FAQ: '{$faq->pertanyaan}' -> Similarity: $similarity%\n";
    if ($similarity > $bestSimilarity) {
        $bestSimilarity = $similarity;
        $bestMatch = $faq;
    }
}

if ($bestMatch && $bestSimilarity >= 50) {
    echo "Best match: {$bestMatch->pertanyaan} -> Jawaban: {$bestMatch->jawaban}\n";
} else {
    echo "Tidak ada match yang cukup baik.\n";
}
?>