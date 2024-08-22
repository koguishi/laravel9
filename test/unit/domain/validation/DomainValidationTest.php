<?php

namespace test\unit\domain\validation;

use core\domain\exception\EntityValidationException;
use core\domain\validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationTest extends TestCase
{
    public function testNotNullException()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullOk()
    {
        try {
            $value = 'qq coisa';
            DomainValidation::notNull($value);

            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullCustomExceptionMessage()
    {
        try {
            $message = 'Custom message';
            $value = '';
            DomainValidation::notNull($value, $message);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, $message);
        }
    }

    public function testStrMaxLenException()
    {
        try {
            $message = 'Custom message';
            $max = 100;
            $value = random_bytes(101);
            DomainValidation::strMaxLen($value, $max, $message);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, $message);
        }
    }

    public function testStrMaxLenOk()
    {
        try {
            $message = 'Custom message';
            $max = 100;
            $value = random_bytes(100);
            DomainValidation::strMaxLen($value, $max, $message);

            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, $message);
        }
    }

    public function testStrMinLen()
    {
        try {
            $message = 'Custom message';
            $min = 3;
            $value = 'AB';
            DomainValidation::strMinLen($value, $min, $message);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, $message);
        }
    }

    public function testStrCanNullButMaxLenException()
    {
        try {
            $value = 'teste';
            DomainValidation::strCanNullButMaxLen($value, 3, 'Custom Messagem');

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
        }
    }

    public function testStrCanNullButMaxLenOK()
    {
        try {
            $value = '12345';
            DomainValidation::strCanNullButMaxLen($value, 10, 'Custom Messagem');

            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
        }
    }

    public function testStrCanNullButMaxLenOKWithEmpty()
    {
        try {
            $value = '';
            DomainValidation::strCanNullButMaxLen($value, 3, 'Custom Messagem');

            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
        }
    }

    public function testStrCanNullButMaxLenOKWithNull()
    {
        try {
            $value = null;
            DomainValidation::strCanNullButMaxLen($value, 3, 'Custom Messagem');

            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
        }
    }

}