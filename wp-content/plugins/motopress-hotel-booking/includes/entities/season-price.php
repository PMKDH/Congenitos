<?php

namespace MPHB\Entities;

class SeasonPrice {

	/**
	 *
	 * @var int
	 */
	private $id;

	/**
	 *
	 * @var int
	 */
	private $seasonId;

	/**
	 *
	 * @var float
	 */
	private $basePrice;

	/**
	 *
	 * @var bool
	 */
	private $enableVariations;

	/**
	 *
	 * @var array
	 */
	private $variations;

	/**
	 *
	 * @param array $atts
	 * @param int $atts['id']
	 * @param int $atts['season_id']
	 * @param float $atts['price']
	 */
	protected function __construct( $atts = array() ){
		$this->id				 = $atts['id'];
		$this->seasonId			 = $atts['season_id'];
		$this->basePrice		 = $atts['price']['base'];
		$this->enableVariations	 = (bool)$atts['price']['enable_variations'];
		$this->variations		 = $atts['price']['variations'];
	}

	/**
	 *
	 * @return int
	 */
	function getId(){
		return $this->id;
	}

	/**
	 *
	 * @return int
	 */
	function getSeasonId(){
		return $this->seasonId;
	}

	/**
	 *
	 * @return \MPHB\Entities\Season|null
	 */
	function getSeason(){
		return MPHB()->getSeasonRepository()->findById( $this->seasonId );
	}

	/**
	 *
	 * @param array $occupancyParams Adults count, children count etc.
	 *
	 * @return float Base or variation price.
	 *
	 * @see mphb_occupancy_parameters()
	 */
	function getPrice( $occupancyParams = array() ){
		$price = $this->basePrice;

		if ( !$this->enableVariations ) {
			return $price;
		}

		$adults = isset( $occupancyParams['adults'] ) ? $occupancyParams['adults'] : '';
		$children = isset( $occupancyParams['children'] ) ? $occupancyParams['children'] : '';

		foreach ( $this->variations as $variation ) {

			if ( $adults == $variation['adults'] && $children == $variation['children'] ) {
				$price = (float)$variation['price'];
			}
		}

		return $price;
	}

	/**
	 *
	 * @return array
	 */
	function getPriceAndVariations(){
		return array(
			'base'				 => $this->basePrice,
			'enable_variations'	 => ( $this->enableVariations ) ? '1' : '0',
			'variations'		 => $this->variations
		);
	}

	/**
	 *
	 * @param array $occupancyParams Adults count, children count etc.
	 *
	 * @return array
	 *
	 * @see mphb_occupancy_parameters()
	 */
	function getDatePrices( $occupancyParams = array() ){
		$season = $this->getSeason();
		if ( !$season ) {
			return array();
		}

		$dates = $season->getDates();
		$dates = array_map( array( '\MPHB\Utils\DateUtils', 'formatDateDB' ), $dates );

		$price = $this->getPrice( $occupancyParams );

		$datePrices = array_fill_keys( $dates, $price );
		return $datePrices;
	}

	/**
	 *
	 * @param array $atts
	 * @param int $atts['id']
	 * @param int $atts['season_id']
	 * @param float $atts['price']
	 * @return SeasonPrice|null
	 */
	public static function create( $atts ){

		if ( !isset( $atts['id'], $atts['price'], $atts['season_id'] ) ) {
			return null;
		}

		$price = $atts['price'];

		$atts['id']			 = (int) $atts['id'];
		$atts['season_id']	 = (int) $atts['season_id'];
		$atts['price']		 = array(
			'base'				 => 0,
			'enable_variations'	 => '0',
			'variations'		 => array()
		);

		if ( is_array( $price ) ) {
			$atts['price'] = array_merge( $atts['price'], $price );
		} else if ( is_numeric( $price ) ) {
			$atts['price']['base'] = (float)$price;
		}

		if ( $atts['id'] < 0 ) {
			return null;
		}

		if ( $atts['price']['base'] < 0 ) {
			return null;
		}

		if ( !MPHB()->getSeasonRepository()->findById( $atts['season_id'] ) ) {
			return null;
		}

		return new self( $atts );
	}

}
