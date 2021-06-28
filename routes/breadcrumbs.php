<?php

// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('داشبورد', route('dashboard'));
});

// Dashboard > Course
Breadcrumbs::for('course.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('دوره ها', route('course.index'));
});

// Dashboard > excel
Breadcrumbs::for('excel', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('اکسل');
});

// Dashboard > Course > Create
Breadcrumbs::for('course.create', function ($trail) {
    $trail->parent('course.index');
    $trail->push('دوره جدید', route('course.create'));
});

// Dashboard > Course > {Course}
Breadcrumbs::for('course.edit', function ($trail , $course) {
    $trail->parent('course.index');
    $trail->push($course->name , route('course.edit' , $course->id));
});

// Dashboard > Course > {Course} > steps
Breadcrumbs::for('course.steps', function ($trail , $course) {
    $trail->parent('course.edit' , $course);
    $trail->push( 'جلسات' , route('course.steps' , $course->id));
});

// Dashboard > Course > {Course} > steps > Create
Breadcrumbs::for('course.steps.create', function ($trail , $course) {
    $trail->parent('course.steps' , $course);
    $trail->push( 'جلسه جدید' , route('course.steps.create' , $course->id));
});

// Dashboard > Course > {Course} > steps > {step}
Breadcrumbs::for('course.steps.edit', function ($trail , $course , $step) {
    $trail->parent('course.steps' , $course);
    $trail->push( $step->name , route('course.steps.edit' , [$course->id , $step->id]));
});

// Dashboard > Course > Trash
Breadcrumbs::for('course.trash', function ($trail) {
    $trail->parent('course.index');
    $trail->push( 'سطل زباله' , route('course.trash'));
});

// Dashboard > Art
Breadcrumbs::for('art.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('دسته ها', route('art.index'));
});

// Dashboard > Art > Create
Breadcrumbs::for('art.create', function ($trail) {
    $trail->parent('art.index');
    $trail->push('دسته جدید', route('art.create'));
});

// Dashboard > Art > Edit
Breadcrumbs::for('art.edit', function ($trail , $art) {
    $trail->parent('art.index');
    $trail->push($art->artName , route('art.edit' , $art->id));
});

// Dashboard > Art > Trash
Breadcrumbs::for('art.trash', function ($trail) {
    $trail->parent('art.index');
    $trail->push('سطل زباله', route('art.trash'));
});

// Dashboard > Art
Breadcrumbs::for('comment.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('کامنت ها', route('comment.index'));
});

// Dashboard > Art > Edit
Breadcrumbs::for('comment.edit', function ($trail , $comment) {
    $trail->parent('comment.index');
    $trail->push($comment->id , route('comment.edit' , $comment->id));
});



// Dashboard > Gift
Breadcrumbs::for('gift.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('تخفیف ها', route('gift.index'));
});

// Dashboard > Gift > Create
Breadcrumbs::for('gift.create', function ($trail) {
    $trail->parent('gift.index');
    $trail->push('تخفیف جدید', route('gift.create'));
});

// Dashboard > Gift > Trash
Breadcrumbs::for('gift.trash', function ($trail) {
    $trail->parent('gift.index');
    $trail->push('سطل زباله', route('gift.trash'));
});

// Dashboard > banner
Breadcrumbs::for('banner.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('بنر ها', route('banner.index'));
});

// Dashboard > banner > most-popular
Breadcrumbs::for('banner.mostPopular.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('هنر های پرطرفدار', route('banner.mostPopular.index'));
});
// Dashboard > banner > most-popular > {banner}
Breadcrumbs::for('banner.mostPopular.edit', function ($trail , $banner) {
    $trail->parent('banner.mostPopular.index');
    $trail->push($banner->position, route('banner.mostPopular.edit' , $banner->id));
});

// Dashboard > banner > most-popular
Breadcrumbs::for('banner.ourOffer.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('پیشنهاد هنری آکادمی', route('banner.ourOffer.index'));
});
// Dashboard > banner > most-popular > {banner}
Breadcrumbs::for('banner.ourOffer.edit', function ($trail , $banner) {
    $trail->parent('banner.ourOffer.index');
    $trail->push($banner->position, route('banner.ourOffer.edit' , $banner->id));
});


// Dashboard > transaction
Breadcrumbs::for('transaction.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('تمامی تراکنش ها', route('transaction.index'));
});

// Dashboard > transaction
Breadcrumbs::for('transaction.addUserClass', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('اضافه کردن کلاس به کاربر', route('transaction.addUserClass'));
});

// Dashboard > teacher
Breadcrumbs::for('teacher.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('اساتید', route('teacher.index'));
});
// Dashboard > teacher > Edit
Breadcrumbs::for('teacher.edit', function ($trail , $teacher) {
    $trail->parent('teacher.index');
    $trail->push($teacher->name, route('teacher.edit' , $teacher->id));
});

// Dashboard > Bundle
Breadcrumbs::for('bundle.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('بسته های آموزشی', route('bundle.index'));
});

// Dashboard > Bundle > Create
Breadcrumbs::for('bundle.create', function ($trail) {
    $trail->parent('bundle.index');
    $trail->push('بسته آموزشی جدید', route('bundle.create'));
});

// Dashboard > Bundle > Edit
Breadcrumbs::for('bundle.edit', function ($trail , $bundle) {
    $trail->parent('bundle.index');
    $trail->push($bundle->name , route('bundle.edit' , $bundle->id));
});
