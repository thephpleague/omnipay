<?php

/*
 * Created by tpay.com.
 * Date: 09.06.2017
 * Time: 17:56
 */
namespace Omnipay\Tpay\Dictionaries\ErrorCodes;

class TransactionApiErrors
{
    const ERROR_CODES = array(
        'ERR44' => 'Invalid transaction id',
        'ERR51' => 'Can\'t create transaction for this channel',
        'ERR52' => 'Error create a transaction, try again later',
        'ERR53' => 'Invalid input data',
        'ERR54' => 'Transation with this id not exists',
        'ERR55' => 'Invalid range or format for dates',
        'ERR99' => 'General error',
        'ERR98' => 'Login error, invalid key or password',
        'ERR97' => 'No metod',
        'ERR31' => 'Access disabled',
        'ERR32' => 'Access forbidden',
        'ERR96' => 'Invalid transaction id or can\'t make refund',
        'ERR4'  => 'Nie został przesłany plik o rozszerzeniu csv',
        'ERR6'  => 'Niepoprawna suma kontrolna (sign)',
        'ERR7'  => 'Niepoprawny format linii',
        'ERR8'  => 'Niepoprawny format numeru rachunku',
        'ERR9'  => 'Nazwa odbiorcy nie może być pusta',
        'ERR10' => 'Nazwa odbiorcy 1 jest za długa - maks. 35 znaków',
        'ERR11' => 'Nazwa odbiorcy 2 jest za długa - maks. 35 znaków',
        'ERR12' => 'Nazwa odbiorcy 3 jest za długa - maks. 35 znaków',
        'ERR13' => 'Nazwa odbiorcy 4 jest za długa - maks. 35 znaków',
        'ERR14' => 'Niepoprawny format kwoty',
        'ERR15' => 'Pole tytuł 1 nie może być puste',
        'ERR16' => 'Pole tytuł 1 jest za długie - maks. 35 znaków',
        'ERR17' => 'Pole tytuł 2 jest za długie - maks. 35 znaków',
        'ERR18' => 'Błąd wewnętrzny',
        'ERR19' => 'Nie udało się załadować pliku o rozszerzeniu csv',
        'ERR20' => 'Błąd przetwarzania przelewów',
        'ERR21' => 'Niepoprawny packId lub nie znaleziono paczki',
        'ERR22' => 'Błąd przy autoryzacji paczki',
        'ERR23' => 'Za mało środków do autoryzacji paczki',
        'ERR24' => 'Paczka została już autoryzowana',
    );
}
