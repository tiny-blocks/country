<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

/**
 * ISO 3166-2 subdivision category, the subdivision type as published by ISO.
 *
 * @see https://www.iso.org/glossary-for-iso-3166.html
 */
enum SubdivisionCategory: string
{
    case AREA = 'Area';
    case CITY = 'City';
    case LAND = 'Land';
    case TOWN = 'Town';
    case WARD = 'Ward';
    case RAYON = 'Rayon';
    case STATE = 'State';
    case CANTON = 'Canton';
    case COUNTY = 'County';
    case ENTITY = 'Entity';
    case ISLAND = 'Island';
    case OBLAST = 'Oblast';
    case PARISH = 'Parish';
    case REGION = 'Region';
    case BOROUGH = 'Borough';
    case CAPITAL = 'Capital';
    case COMMUNE = 'Commune';
    case COUNTRY = 'Country';
    case EMIRATE = 'Emirate';
    case QUARTER = 'Quarter';
    case DISTRICT = 'District';
    case DIVISION = 'Division';
    case PROVINCE = 'Province';
    case REPUBLIC = 'Republic';
    case TERRITORY = 'Territory';
    case DEPARTMENT = 'Department';
    case DEPENDENCY = 'Dependency';
    case POPULARATE = 'Popularate';
    case PREFECTURE = 'Prefecture';
    case STATE_CITY = 'State city';
    case VOIVODSHIP = 'Voivodship';
    case GOVERNORATE = 'Governorate';
    case CAPITAL_CITY = 'Capital city';
    case COUNCIL_AREA = 'Council area';
    case MUNICIPALITY = 'Municipality';
    case SPECIAL_CITY = 'Special city';
    case TOWN_COUNCIL = 'Town council';
    case ARCTIC_REGION = 'Arctic region';
    case LOCAL_COUNCIL = 'Local council';
    case OUTLYING_AREA = 'Outlying area';
    case ADMINISTRATION = 'Administration';
    case FEDERAL_ENTITY = 'Federal entity';
    case ISLAND_COUNCIL = 'Island council';
    case LONDON_BOROUGH = 'London borough';
    case REGIONAL_STATE = 'Regional state';
    case SPECIAL_REGION = 'Special region';
    case AUTONOMOUS_CITY = 'Autonomous city';
    case TWO_TIER_COUNTY = 'Two-tier county';
    case UNION_TERRITORY = 'Union territory';
    case URBAN_COMMUNITY = 'Urban community';
    case CAPITAL_DISTRICT = 'Capital district';
    case CHAIN_OF_ISLANDS = 'Chain (of islands)';
    case CITY_CORPORATION = 'City corporation';
    case FEDERAL_DISTRICT = 'Federal district';
    case TERRITORIAL_UNIT = 'Territorial unit';
    case AUTONOMOUS_REGION = 'Autonomous region';
    case AUTONOMOUS_SECTOR = 'Autonomous sector';
    case CAPITAL_TERRITORY = 'Capital territory';
    case CITY_MUNICIPALITY = 'City municipality';
    case FEDERAL_TERRITORY = 'Federal territory';
    case GEOGRAPHICAL_UNIT = 'Geographical unit';
    case INDIGENOUS_REGION = 'Indigenous region';
    case METROPOLITAN_CITY = 'Metropolitan city';
    case UNITARY_AUTHORITY = 'Unitary authority';
    case FEDERAL_DEPENDENCY = 'Federal dependency';
    case OVERSEAS_TERRITORY = 'Overseas territory';
    case RURAL_MUNICIPALITY = 'Rural municipality';
    case SELF_GOVERNED_PART = 'Self-governed part';
    case URBAN_MUNICIPALITY = 'Urban municipality';
    case AUTONOMOUS_DISTRICT = 'Autonomous district';
    case AUTONOMOUS_PROVINCE = 'Autonomous province';
    case AUTONOMOUS_REPUBLIC = 'Autonomous republic';
    case ECONOMIC_PREFECTURE = 'Economic prefecture';
    case GEOGRAPHICAL_ENTITY = 'Geographical entity';
    case GEOGRAPHICAL_REGION = 'Geographical region';
    case METROPOLITAN_REGION = 'Metropolitan region';
    case ADMINISTRATIVE_ATOLL = 'Administrative atoll';
    case AUTONOMOUS_COMMUNITY = 'Autonomous community';
    case SPECIAL_MUNICIPALITY = 'Special municipality';
    case ADMINISTRATIVE_REGION = 'Administrative region';
    case DISTRICT_MUNICIPALITY = 'District municipality';
    case EUROPEAN_COLLECTIVITY = 'European collectivity';
    case METROPOLITAN_DISTRICT = 'Metropolitan district';
    case OVERSEAS_COLLECTIVITY = 'Overseas collectivity';
    case ADMINISTRATIVE_PRECINCT = 'Administrative precinct';
    case AUTONOMOUS_MUNICIPALITY = 'Autonomous municipality';
    case CITY_WITH_COUNTY_RIGHTS = 'City with county rights';
    case METROPOLITAN_DEPARTMENT = 'Metropolitan department';
    case ADMINISTRATIVE_TERRITORY = 'Administrative territory';
    case SPECIAL_ISLAND_AUTHORITY = 'Special island authority';
    case FEDERAL_CAPITAL_TERRITORY = 'Federal capital territory';
    case FREE_MUNICIPAL_CONSORTIUM = 'Free municipal consortium';
    case ISLANDS_GROUPS_OF_ISLANDS = 'Islands, groups of islands';
    case PAKISTAN_ADMINISTERED_AREA = 'Pakistan administered area';
    case AUTONOMOUS_TERRITORIAL_UNIT = 'Autonomous territorial unit';
    case METROPOLITAN_ADMINISTRATION = 'Metropolitan administration';
    case SPECIAL_ADMINISTRATIVE_CITY = 'Special administrative city';
    case SPECIAL_SELF_GOVERNING_CITY = 'Special self-governing city';
    case DISTRICT_WITH_SPECIAL_STATUS = 'District with special status';
    case DECENTRALIZED_REGIONAL_ENTITY = 'Decentralized regional entity';
    case SPECIAL_ADMINISTRATIVE_REGION = 'Special administrative region';
    case AUTONOMOUS_CITY_IN_NORTH_AFRICA = 'Autonomous city in north africa';
    case SPECIAL_SELF_GOVERNING_PROVINCE = 'Special self-governing province';
    case OVERSEAS_DEPARTMENTAL_COLLECTIVITY = 'Overseas departmental collectivity';
    case GROUP_OF_ISLANDS_20_INHABITED_ISLANDS = 'Group of islands (20 inhabited islands)';
    case DISTRICTS_UNDER_REPUBLIC_ADMINISTRATION = 'Districts under republic administration';
    case OVERSEAS_UNIQUE_TERRITORIAL_COLLECTIVITY = 'Overseas unique territorial collectivity';
    case OVERSEAS_COLLECTIVITY_WITH_SPECIAL_STATUS = 'Overseas collectivity with special status';
    case METROPOLITAN_COLLECTIVITY_WITH_SPECIAL_STATUS = 'Metropolitan collectivity with special status';

    /**
     * Tells whether the category is a state.
     *
     * @return bool True when the category is a state.
     */
    public function isState(): bool
    {
        return $this === self::STATE;
    }

    /**
     * Tells whether the category is a region.
     *
     * @return bool True when the category is a region.
     */
    public function isRegion(): bool
    {
        return $this === self::REGION;
    }

    /**
     * Tells whether the category is a district.
     *
     * @return bool True when the category is a district.
     */
    public function isDistrict(): bool
    {
        return $this === self::DISTRICT;
    }
}
