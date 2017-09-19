<?php

$residents = \App\Models\Resident::current()
    ->orderBy('rank')->orderBy('title_en')->get();

foreach ($residents as $resident) {
    include base_path('legacy') . '/ajax/resident_row.php';
    print('<hr class="border">');
}

?>

<legend>Traveling</legend>

<?php

$residents = \App\Models\Resident::traveling()
    ->orderBy('rank')->orderBy('title_en')->get();

foreach ($residents as $resident) {
    include base_path('legacy') . '/ajax/resident_row.php';
    print('<hr class="border">');
}
