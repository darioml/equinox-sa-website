/*
 * main.c
 *
 *  Created on: 15 Apr 2012
 *      Author: Ashley Grealish
 */

#if defined(__WIN32__) || defined(__WIN32) || defined(_WIN32) || defined(WIN32)
  #define __WIN32__
#endif

#include <stdlib.h>
#include <stdio.h>
#include <stdint.h>
#include <string.h>

#include "unlock_codes.h"



int main()
{
	char unlock_code_str	[50];
	char input_c;
	char box_id_str	[50];
	char filename_str	[50];
	uint32_t unlock_code = 0;
	uint8_t i;
	uint32_t blah;
	FILE* unlock_code_file;
	
	//Clear screen and print header
	#ifdef __WIN32__
		system("cls");
	#else
		//system("clear");
	#endif
	printf("Generate and tests the unlock codes for the stand alone battery box \n\r");
	printf("____________\r\n");



	//Read in the Box ID
	do {
		box_id_str[0] = '\0';
		while( strlen(box_id_str) <= 1 )	//Checks for nothing entered (endline counts as 1 char)
		{
			printf("Please enter the box ID ( 0 -> 16383 ): \n\r");
			fgets( box_id_str, 256, stdin);
		}
		
	} while ( set_box_id( atoi(box_id_str) )  );




	printf("What do you want to do? Press 'g' to generate codes, 'p' for php testing, 't' to test codes\n\r");
	input_c = getchar();
	if (input_c == 'g') {

		//Generate the filename for the output file and open it for writing
		box_id_str[strcspn ( box_id_str, "\n" )] = '\0';		//Removes the newline
		strcpy(filename_str, box_id_str);						//Copies the box_id into the filename
		strcat(filename_str, "_unlock_codes.txt");				//Adds the second part and file extension
		printf("Writing to file: %s\n\r", filename_str);		//Prints out update
		unlock_code_file = fopen(filename_str, "w");			//Opens the file for writing

		//Generate codes for given box_id, each unlocks the box for 2 Weeks
		SET_UNLOCK_DAYS(unlock_code, TWO_WEEKS);

		if (unlock_code_file) {

			//Generate 51 unlock codes
			for (i = 0; i < 51; i++){
				SET_UNLOCK_COUNT(unlock_code, i);
				SET_PAD( unlock_code, rand() )
				add_checksum(&unlock_code);
				fprintf(unlock_code_file, "Code %03i: %010u \n\r", i, unlock_code);
			}

			//Generate a final full unlock code
			SET_UNLOCK_DAYS(unlock_code, FULL_UNLOCK);
			add_checksum(&unlock_code);
			fprintf(unlock_code_file, "Full unlock code: %010u \n\r", unlock_code);

			fclose(unlock_code_file);

			printf("%i unlock codes have been written to the output file \n\r", (i+1) );

		}
		else {
			printf("The file could not be opened \n\r");
		}

	}
	else if (input_c == 'p') {
		blah = 0x0000D77C;
		get_checksum(blah);
		//(uint8_t*)&
		//Fletcher16((uint8_t*)&blah, 6);

	}
	else {
		
		while (1)
		{
		//Read in the unlock code
		unlock_code_str[0] = '\0';
		
		//Checks for nothing entered (endline counts as 1 char)
		while( strlen(unlock_code_str) <= 1 ) 
		{
			printf("\r\nPlease enter the unlock code (ten digits):\r\nInput: ");
			fgets( unlock_code_str, 50, stdin);
		} 

		//Use to strtoul to return an unsigned value
		//atoi returns signed int
		unlock_code = (uint32_t)strtoul(unlock_code_str, NULL, 10);
		//printf("%s \n\r", unlock_code_str);
		printf("\r\nUnlock code hex: 0x%x \r\n", unlock_code);
		printf("Unlock code: %010u \r\n", unlock_code);
		printf("Box ID: %u \r\n\r\n", get_box_id());


		if (check_checksum(unlock_code)) {
			printf("Code checksum correct \r\n\r\n");
			printf("The unlock count is %u \r\n", GET_UNLOCK_COUNT(unlock_code) );
			switch (GET_UNLOCK_DAYS(unlock_code)) {
				case FULL_UNLOCK:
					printf("Full unlock \r\n");
					break;
				case TWO_DAYS:
					printf("Two day unlock \r\n");
					break;
				case FIVE_DAYS:
					printf("Five day unlock \r\n");
					break;
				case SEVEN_DAYS:
					printf("Seven day unlock \r\n");
					break;
				case TWO_WEEKS:
					printf("Two week unlock \r\n");
					break;
				case THREE_WEEKS:
					printf("Three week unlock \r\n");
					break;
				case ONE_MONTH:
					printf("One month unlock \r\n");
					break;
				case TWO_MONTHS:
					printf("Two month unlock \r\n");
					break;
				default:
					printf("Invalid code \r\n");
					break;
			}
		}
		else {
			printf("Code checksum incorrect");
		}
		
		printf("\r\n_____________________\r\n");

	}

	}
	//Keeps the window open until enter is pressed
	getchar();
	return 0;
}

