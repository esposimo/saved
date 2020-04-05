<?php

namespace smn\lazyc\dbc\Catalog;

use PHPUnit\Framework\TestCase;

class EncodingTest extends TestCase
{

    protected $encoding;

    public function testGetCharSet()
    {
        $this->setUp();
        $this->assertEquals($this->encoding->getCharSet(), 'utf8', sprintf('Il charset non coincide con quanto creato'));

    }

    protected function setUp(): void
    {
        $this->encoding = new Encoding('utf8');
    }

    public function testConvert()
    {
        $char = "\u{00c0}";
        /**
         * 0x00c0 in unicode è il simbolo À, in utf8 è rappresentato con 0xc380 e in latin1 diventerà di nuovo 192 (è solo una coincidenza che il carattere unicode e quello latin1 siano uguali)
         * $char viene convertito da unicode a utf8 in automatico da PHP che lavora in UTF-8
         */
        $converted = $this->encoding->convert(new Encoding('latin1'),$char); // qui sto assegnando il valore 0x00c0 unicode ad un encoding in utf8 e convertendo in latin1. Risultato atteso 192
        $this->assertEquals(1,strlen($converted));
        $this->assertEquals(0xc0,ord(substr($converted,0,1)));
    }
}
