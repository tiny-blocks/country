<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use ValueError;

/**
 * Numeric code, a three-digit code that represents a country name, defined by ISO 3166-1.
 *
 * The numeric code is preserved as a string to retain the leading zeros published by ISO
 * (e.g. '076' for Brazil, '840' for the United States of America). Consumers needing the integer
 * form can call toInteger() or cast the value directly.
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 * @see https://www.iso.org/glossary-for-iso-3166.html
 */
enum NumericCode: string implements CountryCode
{
    case AFGHANISTAN = '004';
    case ALBANIA = '008';
    case ALGERIA = '012';
    case AMERICAN_SAMOA = '016';
    case ANDORRA = '020';
    case ANGOLA = '024';
    case ANGUILLA = '660';
    case ANTARCTICA = '010';
    case ANTIGUA_AND_BARBUDA = '028';
    case ARGENTINA = '032';
    case ARMENIA = '051';
    case ARUBA = '533';
    case AUSTRALIA = '036';
    case AUSTRIA = '040';
    case AZERBAIJAN = '031';
    case BAHAMAS = '044';
    case BAHRAIN = '048';
    case BANGLADESH = '050';
    case BARBADOS = '052';
    case BELARUS = '112';
    case BELGIUM = '056';
    case BELIZE = '084';
    case BENIN = '204';
    case BERMUDA = '060';
    case BHUTAN = '064';
    case BOLIVIA = '068';
    case BONAIRE_SINT_EUSTATIUS_AND_SABA = '535';
    case BOSNIA_AND_HERZEGOVINA = '070';
    case BOTSWANA = '072';
    case BOUVET_ISLAND = '074';
    case BRAZIL = '076';
    case BRITISH_INDIAN_OCEAN_TERRITORY = '086';
    case BRUNEI_DARUSSALAM = '096';
    case BULGARIA = '100';
    case BURKINA_FASO = '854';
    case BURUNDI = '108';
    case CABO_VERDE = '132';
    case CAMBODIA = '116';
    case CAMEROON = '120';
    case CANADA = '124';
    case CAYMAN_ISLANDS = '136';
    case CENTRAL_AFRICAN_REPUBLIC = '140';
    case CHAD = '148';
    case CHILE = '152';
    case CHINA = '156';
    case CHRISTMAS_ISLAND = '162';
    case COCOS_KEELING_ISLANDS = '166';
    case COLOMBIA = '170';
    case COMOROS = '174';
    case DEMOCRATIC_REPUBLIC_OF_THE_CONGO = '180';
    case CONGO = '178';
    case COOK_ISLANDS = '184';
    case COSTA_RICA = '188';
    case CROATIA = '191';
    case CUBA = '192';
    case CURACAO = '531';
    case CYPRUS = '196';
    case CZECHIA = '203';
    case COTE_D_IVOIRE = '384';
    case DENMARK = '208';
    case DJIBOUTI = '262';
    case DOMINICA = '212';
    case DOMINICAN_REPUBLIC = '214';
    case ECUADOR = '218';
    case EGYPT = '818';
    case EL_SALVADOR = '222';
    case EQUATORIAL_GUINEA = '226';
    case ERITREA = '232';
    case ESTONIA = '233';
    case ESWATINI = '748';
    case ETHIOPIA = '231';
    case FALKLAND_ISLANDS = '238';
    case FAROE_ISLANDS = '234';
    case FIJI = '242';
    case FINLAND = '246';
    case FRANCE = '250';
    case FRENCH_GUIANA = '254';
    case FRENCH_POLYNESIA = '258';
    case FRENCH_SOUTHERN_TERRITORIES = '260';
    case GABON = '266';
    case GAMBIA = '270';
    case GEORGIA = '268';
    case GERMANY = '276';
    case GHANA = '288';
    case GIBRALTAR = '292';
    case GREECE = '300';
    case GREENLAND = '304';
    case GRENADA = '308';
    case GUADELOUPE = '312';
    case GUAM = '316';
    case GUATEMALA = '320';
    case GUERNSEY = '831';
    case GUINEA = '324';
    case GUINEA_BISSAU = '624';
    case GUYANA = '328';
    case HAITI = '332';
    case HEARD_ISLAND_AND_MCDONALD_ISLANDS = '334';
    case HOLY_SEE = '336';
    case HONDURAS = '340';
    case HONG_KONG = '344';
    case HUNGARY = '348';
    case ICELAND = '352';
    case INDIA = '356';
    case INDONESIA = '360';
    case IRAN = '364';
    case IRAQ = '368';
    case IRELAND = '372';
    case ISLE_OF_MAN = '833';
    case ISRAEL = '376';
    case ITALY = '380';
    case JAMAICA = '388';
    case JAPAN = '392';
    case JERSEY = '832';
    case JORDAN = '400';
    case KAZAKHSTAN = '398';
    case KENYA = '404';
    case KIRIBATI = '296';
    case NORTH_KOREA = '408';
    case SOUTH_KOREA = '410';
    case KUWAIT = '414';
    case KYRGYZSTAN = '417';
    case LAOS = '418';
    case LATVIA = '428';
    case LEBANON = '422';
    case LESOTHO = '426';
    case LIBERIA = '430';
    case LIBYAN_ARAB_JAMAHIRIYA = '434';
    case LIECHTENSTEIN = '438';
    case LITHUANIA = '440';
    case LUXEMBOURG = '442';
    case MACAU = '446';
    case MADAGASCAR = '450';
    case MALAWI = '454';
    case MALAYSIA = '458';
    case MALDIVES = '462';
    case MALI = '466';
    case MALTA = '470';
    case MARSHALL_ISLANDS = '584';
    case MARTINIQUE = '474';
    case MAURITANIA = '478';
    case MAURITIUS = '480';
    case MAYOTTE = '175';
    case MEXICO = '484';
    case MICRONESIA = '583';
    case MOLDOVA = '498';
    case MONACO = '492';
    case MONGOLIA = '496';
    case MONTENEGRO = '499';
    case MONTSERRAT = '500';
    case MOROCCO = '504';
    case MOZAMBIQUE = '508';
    case MYANMAR = '104';
    case NAMIBIA = '516';
    case NAURU = '520';
    case NEPAL = '524';
    case NETHERLANDS = '528';
    case NEW_CALEDONIA = '540';
    case NEW_ZEALAND = '554';
    case NICARAGUA = '558';
    case NIGER = '562';
    case NIGERIA = '566';
    case NIUE = '570';
    case NORFOLK_ISLAND = '574';
    case NORTH_MACEDONIA = '807';
    case NORTHERN_MARIANA_ISLANDS = '580';
    case NORWAY = '578';
    case OMAN = '512';
    case PAKISTAN = '586';
    case PALAU = '585';
    case PALESTINE = '275';
    case PANAMA = '591';
    case PAPUA_NEW_GUINEA = '598';
    case PARAGUAY = '600';
    case PERU = '604';
    case PHILIPPINES = '608';
    case PITCAIRN = '612';
    case POLAND = '616';
    case PORTUGAL = '620';
    case PUERTO_RICO = '630';
    case QATAR = '634';
    case ROMANIA = '642';
    case RUSSIA = '643';
    case RWANDA = '646';
    case REUNION = '638';
    case SAINT_BARTHELEMY = '652';
    case ST_HELENA = '654';
    case SAINT_KITTS_AND_NEVIS = '659';
    case SAINT_LUCIA = '662';
    case SAINT_MARTIN_FRENCH_PART = '663';
    case ST_PIERRE_AND_MIQUELON = '666';
    case SAINT_VINCENT_AND_THE_GRENADINES = '670';
    case SAMOA = '882';
    case SAN_MARINO = '674';
    case SAO_TOME_AND_PRINCIPE = '678';
    case SAUDI_ARABIA = '682';
    case SENEGAL = '686';
    case SERBIA = '688';
    case SEYCHELLES = '690';
    case SIERRA_LEONE = '694';
    case SINGAPORE = '702';
    case SINT_MAARTEN_DUTCH_PART = '534';
    case SLOVAKIA = '703';
    case SLOVENIA = '705';
    case SOLOMON_ISLANDS = '090';
    case SOMALIA = '706';
    case SOUTH_AFRICA = '710';
    case SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS = '239';
    case SOUTH_SUDAN = '728';
    case SPAIN = '724';
    case SRI_LANKA = '144';
    case SUDAN = '729';
    case SURINAME = '740';
    case SVALBARD_AND_JAN_MAYEN_ISLANDS = '744';
    case SWEDEN = '752';
    case SWITZERLAND = '756';
    case SYRIAN_ARAB_REPUBLIC = '760';
    case TAIWAN = '158';
    case TAJIKISTAN = '762';
    case TANZANIA = '834';
    case THAILAND = '764';
    case TIMOR_LESTE = '626';
    case TOGO = '768';
    case TOKELAU = '772';
    case TONGA = '776';
    case TRINIDAD_AND_TOBAGO = '780';
    case TUNISIA = '788';
    case TURKMENISTAN = '795';
    case TURKS_AND_CAICOS_ISLANDS = '796';
    case TUVALU = '798';
    case TURKIYE = '792';
    case UGANDA = '800';
    case UKRAINE = '804';
    case UNITED_ARAB_EMIRATES = '784';
    case UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND = '826';
    case UNITED_STATES_MINOR_OUTLYING_ISLANDS = '581';
    case UNITED_STATES_OF_AMERICA = '840';
    case URUGUAY = '858';
    case UZBEKISTAN = '860';
    case VANUATU = '548';
    case VENEZUELA = '862';
    case VIETNAM = '704';
    case VIRGIN_ISLANDS_BRITISH = '092';
    case VIRGIN_ISLANDS_US = '850';
    case WALLIS_AND_FUTUNA_ISLANDS = '876';
    case WESTERN_SAHARA = '732';
    case YEMEN = '887';
    case ZAMBIA = '894';
    case ZIMBABWE = '716';
    case ALAND_ISLANDS = '248';

    /**
     * Creates a NumericCode from its string value.
     *
     * @param string $code The numeric code (e.g. '076').
     * @return NumericCode The created NumericCode.
     * @throws ValueError If the code matches no known numeric code.
     */
    public static function fromString(string $code): NumericCode
    {
        return NumericCode::from($code);
    }

    /**
     * Creates a NumericCode from its string value, or null when the code is unknown.
     *
     * @param string $code The numeric code (e.g. '076').
     * @return NumericCode|null The created NumericCode, or null when the code matches nothing.
     */
    public static function tryFromString(string $code): ?NumericCode
    {
        return NumericCode::tryFrom($code);
    }

    public function toAlpha2(): Alpha2Code
    {
        return Alpha2Code::{$this->name};
    }

    public function toAlpha3(): Alpha3Code
    {
        return Alpha3Code::{$this->name};
    }

    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Returns the numeric code as an integer, discarding the leading zeros.
     *
     * @return int The numeric value (e.g. 76 for Brazil, 840 for the United States).
     */
    public function toInteger(): int
    {
        return (int)$this->value;
    }

    public function toNumeric(): NumericCode
    {
        return $this;
    }
}
