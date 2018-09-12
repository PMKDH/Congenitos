<?php

namespace MPHB\Repositories;

use \MPHB\Entities;

class RoomRepository extends AbstractPostRepository {

	protected $type = 'room';

	/**
	 *
	 * @param int $id
	 * @param bool $force
	 * @return Entities\Room
	 */
	public function findById( $id, $force = false ){
		return parent::findById( $id, $force );
	}

	function mapPostToEntity( $post ){
		$id = ( is_a( $post, '\WP_Post' ) ) ? $post->ID : $post;
		return new Entities\Room( $id );
	}

	/**
	 *
	 * @param Entities\Room $entity
	 * @return \MPHB\Entities\WPPostData
	 */
	public function mapEntityToPostData( $entity ){
		/**
		 * @todo Why here code from rate?
		 */
		$postAtts = array(
			'ID'			 => $entity->getId(),
			'post_metas'	 => array(),
			'post_status'	 => $entity->isActive() ? 'publish' : 'draft',
			'post_title'	 => $entity->getTitle(),
			'post_content'	 => $entity->getDescription(),
			'post_type'		 => MPHB()->postTypes()->room()->getPostType(),
		);

		$postAtts['post_metas'] = array(
			'mphb_room_type_id'	 => $entity->getRoomTypeId(),
			/**
			 * @todo Serialize SeasonPrice objects?
			 */
			'mphb_season_prices' => array_reverse( $entity->getSeasonPrices() )
		);

		return new Entities\WPPostData( $postAtts );
	}

	/**
	 *
	 * @param Entities\RoomType $roomType
	 * @param int $count Optional. Number of rooms to generate. Default 1.
	 * @param string $customPrefix Optional. Default ''
	 * @return bool
	 */
	public function generateRooms( $roomType, $count = 1, $customPrefix = '' ){
		$titlePrefix = '';

		if ( !$roomType ) {
			return false;
		}

		if ( $count < 1 ) {
			return false;
		}

		if ( empty( $customPrefix ) ) {
			$titlePrefix = $roomType->getTitle() . ' ';
		} else {
			$titlePrefix = $customPrefix . ' ';
		}

		for ( $i = 1; $i <= $count; $i++ ) {
			$postMetaAtts	 = array(
				'mphb_room_type_id' => $roomType->getId(),
			);
			$postDataAtts	 = array(
				'post_metas'	 => $postMetaAtts,
				'post_title'	 => $titlePrefix . $i,
				'post_type'		 => MPHB()->postTypes()->room()->getPostType(),
				'post_status'	 => 'publish'
			);

			$postData = new Entities\WPPostData( $postDataAtts );

			$created = $this->persistence->create( $postData );
		}

		return true;
	}

	public function findAllByRoomType( $roomTypeId, $atts = array() ){
		$atts['room_type_id'] = $roomTypeId;
		return $this->findAll( $atts );
	}

	/**
	 *
	 * @param \DateTime $checkIn
	 * @param \DateTime $checkOut
	 * @param int $roomTypeId
	 *
	 * @return int[] IDs of locked rooms
	 */
	public function getLockedRooms( \DateTime $checkInDate, \DateTime $checkOutDate, $roomTypeId = 0 ){
		$searchAtts = array(
			'availability'	 => 'locked',
			'from_date'		 => $checkInDate,
			'to_date'		 => $checkOutDate
		);

		if ( $roomTypeId ) {
			$searchAtts['room_type_id'] = $roomTypeId;
		}

		$lockedRooms = MPHB()->getRoomPersistence()->searchRooms( $searchAtts );
		$lockedRooms = array_map( 'intval', $lockedRooms );

		return $lockedRooms;
	}

	/**
	 *
	 * @param int $roomTypeId
	 * @param bool $fromToday Optional. True by default.
	 *
	 * @return array Keys are dates in format Y-m-d, values are room counts
	 */
	public function getLockedRoomsCountByDayList( $roomTypeId, $fromToday = true ){

		$roomsIds = MPHB()->getRoomPersistence()->getPosts(
			array(
				'fields'		 => 'ids',
				'room_type_id'	 => $roomTypeId,
				'post_status'	 => 'publish'
			)
		);

		$bookingAtts = array(
			'room_locked'	 => true,
			'rooms'			 => $roomsIds,
			'fields'		 => 'all'
		);

		if ( $fromToday ) {
			$bookingAtts['meta_query'] = array(
				array(
					'key'		 => 'mphb_check_out_date',
					'value'		 => mphb_current_time( 'Y-m-d' ),
					'compare'	 => '>=',
					'type'		 => 'DATE'
				)
			);
		}

		$bookings = MPHB()->getBookingRepository()->findAll( $bookingAtts );

		$dates = array();
		foreach ( $bookings as $key => $booking ) {

			$rooms = array_map( function( Entities\ReservedRoom $reservedRoom ) use ( $roomTypeId ) {
				return $reservedRoom->getRoomTypeId() == $roomTypeId ? $reservedRoom->getRoomId() : null;
			}, $booking->getReservedRooms() );
			$rooms = array_filter( $rooms );

			foreach ( $booking->getDates( $fromToday ) as $dateYmd => $date ) {
				if ( !isset( $dates[$dateYmd] ) ) {
					$dates[$dateYmd] = array();
				}
				$dates[$dateYmd] = array_merge( $dates[$dateYmd], $rooms );
			}
		}

		$dates	 = array_filter( $dates );
		$dates	 = array_map( 'array_unique', $dates );
		$dates	 = array_map( 'count', $dates );
		ksort( $dates );

		return $dates;
	}

	/**
	 *
	 * @global \wpdb $wpdb
	 *
	 * @param \DateTime $checkIn
	 * @param \DateTime $checkOut
	 * @param type $roomTypeId
	 *
	 * @return array [%Room type ID% => [%Rooms IDs%]]
	 */
	public function getAvailableRooms( \DateTime $checkInDate, \DateTime $checkOutDate, $roomTypeId = 0 ){
		global $wpdb;

		$lockedRooms = $this->getLockedRooms( $checkInDate, $checkOutDate, $roomTypeId );

		$query = "SELECT room_type_id.meta_value AS type_id, rooms.ID AS room_id "
			. "FROM $wpdb->posts AS rooms "

			. "INNER JOIN $wpdb->postmeta AS room_type_id "
			. "ON rooms.ID = room_type_id.post_id "
			. "INNER JOIN $wpdb->posts AS room_types "
			. "ON room_type_id.meta_value = room_types.ID "

			. "WHERE rooms.post_type = '" . MPHB()->postTypes()->room()->getPostType() . "' "
			. "AND rooms.post_status = 'publish' "
			. "AND room_type_id.meta_key = 'mphb_room_type_id' "
			. "AND room_types.post_status = 'publish' "
			. "AND room_types.post_type = '" . MPHB()->postTypes()->roomType()->getPostType() . "' ";

		if ( !empty( $lockedRooms ) ) {
			$query .= "AND rooms.ID NOT IN (" . join( ',', $lockedRooms ) . ") ";
		}

		if ( $roomTypeId > 0 ) {
			$query .= "AND room_type_id.meta_value = '$roomTypeId' ";
		} else {
			$query .= "AND room_type_id.meta_value IS NOT NULL "
				. "AND room_type_id.meta_value <> '' ";
		}

		/**
		 * @var array [["type_id", "room_id"], ...]
		 */
		$results = $wpdb->get_results( $query, ARRAY_A );

		$availableRooms = array();

		foreach ( $results as $row ) {
			$typeId = intval( $row['type_id'] );
			$roomId = intval( $row['room_id'] );

			if ( !isset( $availableRooms[$typeId] ) ) {
				$availableRooms[$typeId] = array();
			}

			$availableRooms[$typeId][] = $roomId;
		}

		return $availableRooms;
	}

}
