<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
$this->registerCssFile('@web/css/styles.css');
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Agenda Telefonica</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h2 class="card-title">Agregar Contacto</h2>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'contact-form',
                'action' => ['contact/create'],
                'method' => 'post',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <div class="form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group text-center">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <h2 class="text-center mb-4">Contactos</h2>
    <ul id="contact-list" class="list-group">
        <!-- Aquí se mostrarán los contactos -->
    </ul>
</div>

<?php
$this->registerJs("
    function loadContacts() {
        $.get('index.php?r=contact/get-contacts', function(data) {
            var contactList = $('#contact-list');
            contactList.empty();
            data.forEach(function(contact, index) {
                contactList.append('<li class=\"list-group-item d-flex justify-content-between align-items-center\">' + contact.name + ' - ' + contact.phone + ' - ' + contact.email + ' <div><button class=\"btn btn-sm btn-warning edit-contact mr-2\" data-index=\"' + index + '\">Editar</button> <button class=\"btn btn-sm btn-danger delete-contact\" data-index=\"' + index + '\">Eliminar</button></div></li>');
            });
        });
    }

    $('#contact-form').on('beforeSubmit', function(e) {
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(response) {
                if(response.status === 'success') {
                    loadContacts();
                    form[0].reset();
                } else {
                    alert('Error: ' + JSON.stringify(response.errors));
                }
            }
        });
        return false;
    });

    $(document).on('click', '.delete-contact', function() {
        var index = $(this).data('index');
        $.post('index.php?r=contact/delete', {index: index}, function(response) {
            if(response.status === 'success') {
                loadContacts();
            } else {
                alert('Error: ' + JSON.stringify(response.errors));
            }
        });
    });

    $(document).on('click', '.edit-contact', function() {
        var index = $(this).data('index');
        $.get('index.php?r=contact/get-contacts', function(data) {
            var contact = data[index];
            $('#contact-name').val(contact.name);
            $('#contact-phone').val(contact.phone);
            $('#contact-email').val(contact.email);
            $('#contact-form').attr('action', 'index.php?r=contact/update&index=' + index);
        });
    });

    loadContacts();
");
?>
