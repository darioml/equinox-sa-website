/*
 * unlock_codes.h
 *
 *  Created on: 16 Apr 2012
 *      Author: Ashley Grealish
 */

#ifndef UNLOCK_CODES_H_
#define UNLOCK_CODES_H_

	#include <stdlib.h>
	#include <stdio.h>
	#include <stdint.h>
	#include <string.h>

	//Pre-processor
	#define SHARED_SECRET ( (uint16_t)0x259c )

	//(0= full unlock, 1=2 day, 2= 5 days, 3 = 7 days, 4 =  2 weeks, 5 = 3 weeks, 6= 1month, 7= 2 months)
	#define FULL_UNLOCK				0
	#define	TWO_DAYS				1
	#define	FIVE_DAYS				2
	#define	SEVEN_DAYS				3
	#define	TWO_WEEKS				4
	#define	THREE_WEEKS				5
	#define	ONE_MONTH				6
	#define	TWO_MONTHS				7

	#define	SET_UNLOCK_DAYS(x,y)  	x &= ~0x7;		x |= (y & 0x7)
	#define GET_UNLOCK_DAYS(x)		(x & 0x7)
	#define SET_UNLOCK_COUNT(x,y)	x &= ~((uint32_t)0xFF << 3);		x |= (uint32_t)(y & 0xFF) << 3
	#define GET_UNLOCK_COUNT(x)		(((uint32_t)x >> 3) & 0xFF)
	#define	SET_PAD(x,y)			x &= ~((uint32_t)0x1F << 11);		x |= (uint32_t)(y & 0x1F) << 11;
	#define	GET_PAD(x)				(((uint32_t)x >> 11) & 0x1F)
	#define SET_CHECKSUM(x,y)		x &= ~((uint32_t)0xFFFF << 16);		x |= (uint32_t)(y & 0xFFFF) << 16
	#define GET_CHECKSUM(x)			(((uint32_t)x >> 16) & 0xFFFF)


	//Function Prototypes
	char 		set_box_id		(uint16_t);
	uint16_t 	get_box_id 		(void) ;
	uint16_t 	Fletcher16		( uint8_t*, int );
	void 		add_checksum	( uint32_t*);
	uint16_t 	get_checksum	( uint32_t data );
	char 		check_checksum	( uint32_t );

#endif /* UNLOCK_CODES_H_ */
