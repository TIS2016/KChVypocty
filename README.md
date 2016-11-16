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