# KChVypocty
Kvantovo-chemické výpočty

Inštalácia

```
1. git clone project
2. composer install

```

Inicializacia databázy:

```

1. Nastaviť root lokálneho apache serveru na zložku s projektom
1. Vytvoriť si prázdnu databázu na lokanom mysql serveri + správne nakonfigurovať PHP/Apache
2. V súbore `bootstrap.php` treba natstaviť prihlasovacie udaje k databáze
3. Spustiť príkaz: `vendor/bin/doctrine orm:schema-tool:update --force --dump-sql`  , ktorý vytvorí databázu

```

Spustenie parseru s lexerom

```

$lexer = new Lexer('sample_data/leucine-1b1-G3MP2.log');
$calculations = $lexer->getCalculations();

if (empty($calculations)) {
    echo $lexer->getErrorMessage();
} else {
    $parser = new Parser($calculations);
    $parser->setSpecialCharacter($lexer->getSpecialCharacter());
    $parser->setPath($lexer->getPath());
    $parser->setFile($lexer->getFile());
    $parser->parseCalculations();
}

```
