/*
 * unlock_codes.c
 *
 *  Created on: 16 Apr 2012
 *      Author: Ashley Grealish
 */

#include "unlock_codes.h"

//Global variables
uint16_t box_id = 0;


char set_box_id(uint16_t box_id_in) {

	if ( (box_id_in < 1) || (box_id_in > 65535)) //16383
		//Fail
		return 1;
	else {
		//Success
		box_id = box_id_in;
		return 0;
	}
}

uint16_t get_box_id (void) {
	return box_id;
}

uint16_t get_checksum( uint32_t data ) {
	//Calculate the checksum using the data packet, the box_id and the shared secret
	uint32_t checksum_data [2];

        checksum_data[0] = ((uint32_t)box_id << 16) | SHARED_SECRET;
        checksum_data[1] = (uint32_t)(data & 0xFFFF);

	return Fletcher16( (uint8_t*)&checksum_data, 6) ;
}

void add_checksum( uint32_t* data) {
	uint16_t checksum = 0;

	SET_CHECKSUM( *data, 0 );

	checksum = get_checksum(*data);

	SET_CHECKSUM( *data, checksum);

	return;
}

char check_checksum( uint32_t data ) {
	uint16_t checksum_calc, checksum_reciv;

	//Holds the received checksum
	checksum_reciv = GET_CHECKSUM(data);

	//Clears the checksum from the current data
	SET_CHECKSUM( data, 0 );

	//Recalculates the checksum on the normal data
	checksum_calc = get_checksum(data) ;

	//Checks for matching checksums
	if ( checksum_reciv == checksum_calc )
		return 1;
	else
		return 0;

}

uint16_t Fletcher16( uint8_t* data, int count )
{
   uint16_t sum1 = 0;
   uint16_t sum2 = 0;
   int index;

   for( index = 0; index < count; ++index )
   {
      
      sum1 = (sum1 + data[index]) % 255;
      sum2 = (sum2 + sum1) % 255;
   }
   return (sum2 << 8) | sum1;
}


