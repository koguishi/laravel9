<?php

namespace Tests\Unit\domain\notification;

use core\domain\notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();

        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddError()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();
        $this->assertCount(0, $errors);

        $notification->addError([
            'context' => 'video',
            'message' => 'mensagem de erro',
        ]);
        $errors = $notification->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testHasError()
    {
        $notification = new Notification();
        $this->assertFalse($notification->hasError());

        $notification->addError([
            'context' => 'video',
            'message' => 'mensagem de erro',
        ]);

        $this->assertTrue($notification->hasError());
    }

    public function testMessage()
    {
        $notification = new Notification();

        $notification->addError([
            'context' => 'video',
            'message' => 'Título é requerido',
        ]);

        $notification->addError([
            'context' => 'categoria',
            'message' => 'Nome é requerido',
        ]);
        $expectedMessage = 'video: Título é requerido; categoria: Nome é requerido; ';

        $message = $notification->getMessage();
        $this->assertIsString($message);

        $this->assertEquals($expectedMessage, $message);
    }

    public function testMessageByContext()
    {
        $notification = new Notification();

        $notification->addError([
            'context' => 'video',
            'message' => 'Título é requerido',
        ]);

        $notification->addError([
            'context' => 'categoria',
            'message' => 'Nome é requerido',
        ]);
        $expectedMessage = 'video: Título é requerido; ';

        $message = $notification->getMessage(context: 'video');
        $this->assertIsString($message);

        $this->assertEquals($expectedMessage, $message);
    }

}
