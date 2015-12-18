<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<h1>Hello, <?= $model->getUsername();?></h1>
<h3>
    <p>Wallet:: <?= $model->getCash(); ?></p>
</h3>

<?php if(isset($model->error)): ?>
    <h2>An error occurred</h2>
<?php elseif(isset($model->success)): ?>
    <h2>Successfully updated profile</h2>
<?php endif; ?>

<?php
    \ShoppingCart\ViewHelpers\RadioCheckViewHelper::create()
        ->typeCheckbox()
        ->setName("gender")
        ->setValueAndIdentifier([
            "male" => "Male",
            "female" => "Female",
        ])
        ->setSubmit("Submit")
        ->setCheckedIdentifier("Male")
        ->render();
?>

<?php
\ShoppingCart\ViewHelpers\DropDownViewHelper::create()
->setAction("action.php")
->setName("cars")
->setValueAndIdentifier([
    "reno" => "Reno",
    "opel" => "Opel",
    "audi" => "Audi",
    "bmw" => "BMW"
])
    ->setSelectedIdentifier("Audi")
    ->setSubmit("Submit")
->render();
?>

<div>
Edit Profile:
<?php
\ShoppingCart\ViewHelpers\AjaxSubmitViewHelper::create()
    ->setMethodt("post")
    ->setButtonId("ajax")
    ->setUrl("http://localhost:8080/ShoppingCart/users/editProfile")
    ->setTextFields([
        "username" => "text",
        "password" => "password",
        "confirm" => "password"
    ])
    ->render();
?>
</div>

Go to:
<div class="menu">
    <a href="buildings.php">Buildings</a>
</div>
