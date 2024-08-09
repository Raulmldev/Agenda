<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Contact;

class ContactController extends Controller
{
    public function actionIndex()
    {
        $model = new Contact();
        return $this->render('index', ['model' => $model]);

        /*Yii::$app->response->format = Response::FORMAT_JSON;
       *$filePath = Yii::getAlias('@app/contacts.json');
        *$contacts = json_decode(file_get_contents($filePath), true);
        return $contacts;*/
    }

    public function actionCreate()
    {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $contact = new Contact();
    if ($contact->load(Yii::$app->request->post()) && $contact->validate()) {
        $filePath = Yii::getAlias('@app/contacts.json');
        $contacts = json_decode(file_get_contents($filePath), true);
        $contacts[] = $contact->attributes;
        file_put_contents($filePath, json_encode($contacts));
        return ['status' => 'success'];
    }
    return ['status' => 'error', 'errors' => $contact->errors];    
    /*Yii::$app->response->format = Response::FORMAT_JSON;
        $contact = new Contact();
        if ($contact->load(Yii::$app->request->post()) && $contact->validate()) {
            $filePath = Yii::getAlias('@app/contacts.json');
            $contacts = json_decode(file_get_contents($filePath), true);
            $contacts[] = $contact->attributes;
            file_put_contents($filePath, json_encode($contacts));
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'errors' => $contact->errors];*/

    }

    public function actionGetContacts()
{
     Yii::$app->response->format = Response::FORMAT_JSON;
    $filePath = Yii::getAlias('@app/contacts.json');
    $contacts = json_decode(file_get_contents($filePath), true);
    return $contacts;
}

public function actionDelete()
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    $index = Yii::$app->request->post('index');
    $filePath = Yii::getAlias('@app/contacts.json');
    $contacts = json_decode(file_get_contents($filePath), true);
    if (isset($contacts[$index])) {
        array_splice($contacts, $index, 1);
        file_put_contents($filePath, json_encode($contacts));
        return ['status' => 'success'];
    }
    return ['status' => 'error', 'message' => 'Contact not found'];
}

public function actionUpdate($index)
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    $contact = new Contact();
    if ($contact->load(Yii::$app->request->post()) && $contact->validate()) {
        $filePath = Yii::getAlias('@app/contacts.json');
        $contacts = json_decode(file_get_contents($filePath), true);
        if (isset($contacts[$index])) {
            $contacts[$index] = $contact->attributes;
            file_put_contents($filePath, json_encode($contacts));
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Contact not found'];
    }
    return ['status' => 'error', 'errors' => $contact->errors];
}


}
?>