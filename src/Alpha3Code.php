<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use ValueError;

/**
 * Alpha-3 code, a three-letter code that represents a country name, usually more closely related
 * to the country name than its Alpha-2 counterpart.
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 * @see https://www.iso.org/glossary-for-iso-3166.html
 */
enum Alpha3Code: string implements CountryCode
{
    case AFGHANISTAN = 'AFG';
    case ALBANIA = 'ALB';
    case ALGERIA = 'DZA';
    case AMERICAN_SAMOA = 'ASM';
    case ANDORRA = 'AND';
    case ANGOLA = 'AGO';
    case ANGUILLA = 'AIA';
    case ANTARCTICA = 'ATA';
    case ANTIGUA_AND_BARBUDA = 'ATG';
    case ARGENTINA = 'ARG';
    case ARMENIA = 'ARM';
    case ARUBA = 'ABW';
    case AUSTRALIA = 'AUS';
    case AUSTRIA = 'AUT';
    case AZERBAIJAN = 'AZE';
    case BAHAMAS = 'BHS';
    case BAHRAIN = 'BHR';
    case BANGLADESH = 'BGD';
    case BARBADOS = 'BRB';
    case BELARUS = 'BLR';
    case BELGIUM = 'BEL';
    case BELIZE = 'BLZ';
    case BENIN = 'BEN';
    case BERMUDA = 'BMU';
    case BHUTAN = 'BTN';
    case BOLIVIA = 'BOL';
    case BONAIRE_SINT_EUSTATIUS_AND_SABA = 'BES';
    case BOSNIA_AND_HERZEGOVINA = 'BIH';
    case BOTSWANA = 'BWA';
    case BOUVET_ISLAND = 'BVT';
    case BRAZIL = 'BRA';
    case BRITISH_INDIAN_OCEAN_TERRITORY = 'IOT';
    case BRUNEI_DARUSSALAM = 'BRN';
    case BULGARIA = 'BGR';
    case BURKINA_FASO = 'BFA';
    case BURUNDI = 'BDI';
    case CABO_VERDE = 'CPV';
    case CAMBODIA = 'KHM';
    case CAMEROON = 'CMR';
    case CANADA = 'CAN';
    case CAYMAN_ISLANDS = 'CYM';
    case CENTRAL_AFRICAN_REPUBLIC = 'CAF';
    case CHAD = 'TCD';
    case CHILE = 'CHL';
    case CHINA = 'CHN';
    case CHRISTMAS_ISLAND = 'CXR';
    case COCOS_KEELING_ISLANDS = 'CCK';
    case COLOMBIA = 'COL';
    case COMOROS = 'COM';
    case DEMOCRATIC_REPUBLIC_OF_THE_CONGO = 'COD';
    case CONGO = 'COG';
    case COOK_ISLANDS = 'COK';
    case COSTA_RICA = 'CRI';
    case CROATIA = 'HRV';
    case CUBA = 'CUB';
    case CURACAO = 'CUW';
    case CYPRUS = 'CYP';
    case CZECHIA = 'CZE';
    case COTE_D_IVOIRE = 'CIV';
    case DENMARK = 'DNK';
    case DJIBOUTI = 'DJI';
    case DOMINICA = 'DMA';
    case DOMINICAN_REPUBLIC = 'DOM';
    case ECUADOR = 'ECU';
    case EGYPT = 'EGY';
    case EL_SALVADOR = 'SLV';
    case EQUATORIAL_GUINEA = 'GNQ';
    case ERITREA = 'ERI';
    case ESTONIA = 'EST';
    case ESWATINI = 'SWZ';
    case ETHIOPIA = 'ETH';
    case FALKLAND_ISLANDS = 'FLK';
    case FAROE_ISLANDS = 'FRO';
    case FIJI = 'FJI';
    case FINLAND = 'FIN';
    case FRANCE = 'FRA';
    case FRENCH_GUIANA = 'GUF';
    case FRENCH_POLYNESIA = 'PYF';
    case FRENCH_SOUTHERN_TERRITORIES = 'ATF';
    case GABON = 'GAB';
    case GAMBIA = 'GMB';
    case GEORGIA = 'GEO';
    case GERMANY = 'DEU';
    case GHANA = 'GHA';
    case GIBRALTAR = 'GIB';
    case GREECE = 'GRC';
    case GREENLAND = 'GRL';
    case GRENADA = 'GRD';
    case GUADELOUPE = 'GLP';
    case GUAM = 'GUM';
    case GUATEMALA = 'GTM';
    case GUERNSEY = 'GGY';
    case GUINEA = 'GIN';
    case GUINEA_BISSAU = 'GNB';
    case GUYANA = 'GUY';
    case HAITI = 'HTI';
    case HEARD_ISLAND_AND_MCDONALD_ISLANDS = 'HMD';
    case HOLY_SEE = 'VAT';
    case HONDURAS = 'HND';
    case HONG_KONG = 'HKG';
    case HUNGARY = 'HUN';
    case ICELAND = 'ISL';
    case INDIA = 'IND';
    case INDONESIA = 'IDN';
    case IRAN = 'IRN';
    case IRAQ = 'IRQ';
    case IRELAND = 'IRL';
    case ISLE_OF_MAN = 'IMN';
    case ISRAEL = 'ISR';
    case ITALY = 'ITA';
    case JAMAICA = 'JAM';
    case JAPAN = 'JPN';
    case JERSEY = 'JEY';
    case JORDAN = 'JOR';
    case KAZAKHSTAN = 'KAZ';
    case KENYA = 'KEN';
    case KIRIBATI = 'KIR';
    case NORTH_KOREA = 'PRK';
    case SOUTH_KOREA = 'KOR';
    case KUWAIT = 'KWT';
    case KYRGYZSTAN = 'KGZ';
    case LAOS = 'LAO';
    case LATVIA = 'LVA';
    case LEBANON = 'LBN';
    case LESOTHO = 'LSO';
    case LIBERIA = 'LBR';
    case LIBYAN_ARAB_JAMAHIRIYA = 'LBY';
    case LIECHTENSTEIN = 'LIE';
    case LITHUANIA = 'LTU';
    case LUXEMBOURG = 'LUX';
    case MACAU = 'MAC';
    case MADAGASCAR = 'MDG';
    case MALAWI = 'MWI';
    case MALAYSIA = 'MYS';
    case MALDIVES = 'MDV';
    case MALI = 'MLI';
    case MALTA = 'MLT';
    case MARSHALL_ISLANDS = 'MHL';
    case MARTINIQUE = 'MTQ';
    case MAURITANIA = 'MRT';
    case MAURITIUS = 'MUS';
    case MAYOTTE = 'MYT';
    case MEXICO = 'MEX';
    case MICRONESIA = 'FSM';
    case MOLDOVA = 'MDA';
    case MONACO = 'MCO';
    case MONGOLIA = 'MNG';
    case MONTENEGRO = 'MNE';
    case MONTSERRAT = 'MSR';
    case MOROCCO = 'MAR';
    case MOZAMBIQUE = 'MOZ';
    case MYANMAR = 'MMR';
    case NAMIBIA = 'NAM';
    case NAURU = 'NRU';
    case NEPAL = 'NPL';
    case NETHERLANDS = 'NLD';
    case NEW_CALEDONIA = 'NCL';
    case NEW_ZEALAND = 'NZL';
    case NICARAGUA = 'NIC';
    case NIGER = 'NER';
    case NIGERIA = 'NGA';
    case NIUE = 'NIU';
    case NORFOLK_ISLAND = 'NFK';
    case NORTH_MACEDONIA = 'MKD';
    case NORTHERN_MARIANA_ISLANDS = 'MNP';
    case NORWAY = 'NOR';
    case OMAN = 'OMN';
    case PAKISTAN = 'PAK';
    case PALAU = 'PLW';
    case PALESTINE = 'PSE';
    case PANAMA = 'PAN';
    case PAPUA_NEW_GUINEA = 'PNG';
    case PARAGUAY = 'PRY';
    case PERU = 'PER';
    case PHILIPPINES = 'PHL';
    case PITCAIRN = 'PCN';
    case POLAND = 'POL';
    case PORTUGAL = 'PRT';
    case PUERTO_RICO = 'PRI';
    case QATAR = 'QAT';
    case ROMANIA = 'ROU';
    case RUSSIA = 'RUS';
    case RWANDA = 'RWA';
    case REUNION = 'REU';
    case SAINT_BARTHELEMY = 'BLM';
    case ST_HELENA = 'SHN';
    case SAINT_KITTS_AND_NEVIS = 'KNA';
    case SAINT_LUCIA = 'LCA';
    case SAINT_MARTIN_FRENCH_PART = 'MAF';
    case ST_PIERRE_AND_MIQUELON = 'SPM';
    case SAINT_VINCENT_AND_THE_GRENADINES = 'VCT';
    case SAMOA = 'WSM';
    case SAN_MARINO = 'SMR';
    case SAO_TOME_AND_PRINCIPE = 'STP';
    case SAUDI_ARABIA = 'SAU';
    case SENEGAL = 'SEN';
    case SERBIA = 'SRB';
    case SEYCHELLES = 'SYC';
    case SIERRA_LEONE = 'SLE';
    case SINGAPORE = 'SGP';
    case SINT_MAARTEN_DUTCH_PART = 'SXM';
    case SLOVAKIA = 'SVK';
    case SLOVENIA = 'SVN';
    case SOLOMON_ISLANDS = 'SLB';
    case SOMALIA = 'SOM';
    case SOUTH_AFRICA = 'ZAF';
    case SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS = 'SGS';
    case SOUTH_SUDAN = 'SSD';
    case SPAIN = 'ESP';
    case SRI_LANKA = 'LKA';
    case SUDAN = 'SDN';
    case SURINAME = 'SUR';
    case SVALBARD_AND_JAN_MAYEN_ISLANDS = 'SJM';
    case SWEDEN = 'SWE';
    case SWITZERLAND = 'CHE';
    case SYRIAN_ARAB_REPUBLIC = 'SYR';
    case TAIWAN = 'TWN';
    case TAJIKISTAN = 'TJK';
    case TANZANIA = 'TZA';
    case THAILAND = 'THA';
    case TIMOR_LESTE = 'TLS';
    case TOGO = 'TGO';
    case TOKELAU = 'TKL';
    case TONGA = 'TON';
    case TRINIDAD_AND_TOBAGO = 'TTO';
    case TUNISIA = 'TUN';
    case TURKMENISTAN = 'TKM';
    case TURKS_AND_CAICOS_ISLANDS = 'TCA';
    case TUVALU = 'TUV';
    case TURKIYE = 'TUR';
    case UGANDA = 'UGA';
    case UKRAINE = 'UKR';
    case UNITED_ARAB_EMIRATES = 'ARE';
    case UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND = 'GBR';
    case UNITED_STATES_MINOR_OUTLYING_ISLANDS = 'UMI';
    case UNITED_STATES_OF_AMERICA = 'USA';
    case URUGUAY = 'URY';
    case UZBEKISTAN = 'UZB';
    case VANUATU = 'VUT';
    case VENEZUELA = 'VEN';
    case VIETNAM = 'VNM';
    case VIRGIN_ISLANDS_BRITISH = 'VGB';
    case VIRGIN_ISLANDS_US = 'VIR';
    case WALLIS_AND_FUTUNA_ISLANDS = 'WLF';
    case WESTERN_SAHARA = 'ESH';
    case YEMEN = 'YEM';
    case ZAMBIA = 'ZMB';
    case ZIMBABWE = 'ZWE';
    case ALAND_ISLANDS = 'ALA';

    /**
     * Creates an Alpha3Code from its string value.
     *
     * @param string $code The Alpha-3 code (e.g. 'BRA').
     * @return Alpha3Code The created Alpha3Code.
     * @throws ValueError If the code matches no known Alpha-3 code.
     */
    public static function fromString(string $code): Alpha3Code
    {
        return Alpha3Code::from($code);
    }

    /**
     * Creates an Alpha3Code from its string value, or null when the code is unknown.
     *
     * @param string $code The Alpha-3 code (e.g. 'BRA').
     * @return Alpha3Code|null The created Alpha3Code, or null when the code matches nothing.
     */
    public static function tryFromString(string $code): ?Alpha3Code
    {
        return Alpha3Code::tryFrom($code);
    }

    public function toAlpha2(): Alpha2Code
    {
        return Alpha2Code::{$this->name};
    }

    public function toAlpha3(): Alpha3Code
    {
        return $this;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toNumeric(): NumericCode
    {
        return NumericCode::{$this->name};
    }
}
