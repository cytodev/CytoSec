/**
 * Crypter.h
 *
 * Encodes and decodes a string of data. This can be anything, ranging from the
 * name of your cat to that one person who you'd like to compile.  Hell, it can
 * even be a super secret message that you don't want the NSA to read.
 *
 * Usage:    You  take this file, and you place it in your  working  directory.
 *           You  add  it as a dependency to your main.c file and  you're  done
 *           - well, not  really. You  need to rewrite everything here  because
 *           I placed some insects all over the place. Or maybe they've starved
 *           already - I don't know.
 *
 * Encoding: call encode().  He'll pick up the phone  and ask you what you want
 *           from him.  You'll  reply saying that you have a nice <String>  for
 *           him to munch on.  -  He is still listening  -  Now you realise you
 *           haven't  even offered  him a plate!  "Oh no!"  You  scream as  you
 *           realise you've just segfaulted all over the place.
 *
 *           encode() expects your input  <String>  and an empty char  array of
 *           <PLACE_NUMBER_HERE> size.  Without the first he  just looks at you
 *           without doing  anything useful.  Without  the second  he drops the
 *           phone,  grabs  his shotgun,   murders your family,  and stalks you
 *           until you decide to commit suicide. - Yes, he's a nasty piece...
 *
 * Decoding: ring decode()'s doorbell.  She'll open the  door and smile Kindly.
 *           After you stare at her with a  blank face for a while you remember
 *           what you where going to ask her.
 *
 *           As  you  hand her  your encoded  string of text you  remember that
 *           encode() demanded an  empty array of  <PLACE_NUMBER_HERE> while he
 *           stomped your face with a stapler.
 *
 *           You  quickly run back to your car and grab the empty string  array
 *           from  the  passenger  seat.  As  you make  your  way back  to  her
 *           appartement the whole block suddenly sinks into the ground...  You
 *           where  too late.  decode() has  already  segfaulted and  created a
 *           black  hole that  will  soon devour  everything in  both the  main
 *           universe as well as the restricted multiverse.
 *
 * Safe:     To keep  you safe  (since you're a user,  and users break stuff) I
 *           have implemented a safe  function that you absolutely should never
 *           use if you want to turn your hard drive into one big core dump.
 *
 *           crypter() is the safe function's name.  It's genderless because it
 *           is just an abstract idea represented by a few lines of meaningless
 *           symbols we humans call text.
 *
 *           crypter() will take a few arguments to know what you want from our
 *           friends encode() and decode()  along with a nice useQA64(R) shift.
 *
 *           When you tell crypter()  to use  useQA64(R),  you will get results
 *           not from the base64 table, but from the qa64(C) table.  This table
 *           consists of the same alphanumeric characters (with + and /) as the
 *           original base64 table, but is arranged differently.
 *
 * @author Roel Walraven <roelwalraven7@gmail.com>
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

/**
 * b64[] (array)
 *
 * an array containing the base64 encoding table.
 *
 * The base64 encoding table can be modified to shift characters. This way a
 * conventional converter cannot decode it and this one must be used on the
 * decoder's side. (but then again, it will no longer be base64 now would it?)
 */
char b64[]  = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

/**
 * qa64[] (array)
 *
 * an array containing the base64 encoding table in a different format.
 *
 * Here, The base64 has been modified to shift characters. This way a
 * conventional converter cannot decode it and this one must be used on the
 * decoder's side.
 *
 * this encodes the string "test" as "rUCnrQ==" instead of base64: "dGVzdA=="
 */
char qa64[] = "QWERTYUIOPASDFGHJKLZXCVBNMazertyuiopqsdfghjklmwxcvbn1357924680+/";

/**
 * decodeblock
 *
 * decode 4 '6-bit' characters into 3 8-bit binary bytes.
 *
 * @param in[]   (unsigned char)
 * @param clrstr (char *)
 */
void decodeblock(unsigned char in[], char *clrstr) {
    unsigned char out[4];

    out[0] = in[0] << 2 | in[1] >> 4;
    out[1] = in[1] << 4 | in[2] >> 2;
    out[2] = in[2] << 6 | in[3] >> 0;
    out[3] = '\0';

    strncat(clrstr, out, sizeof(out));
}

/**
 * decode
 *
 * name says all.
 *
 * @param data    (char *)
 * @param decoded (char *)
 */
void decode(char *data, char *decoded) {
    int c
      , phase
      , i
      ;
    unsigned char in[4];
    char *p;

    decoded[0] = '\0';
    phase = 0; i=0;

    while(data[i]) {
        c = (int) data[i];

        if(c == '=') {
            decodeblock(in, decoded); 
            break;
        }

        p = strchr(b64, c);

        if(p) {
            in[phase] = p - b64;
            phase = (phase + 1) % 4;

            if(phase == 0) {
                decodeblock(in, decoded);
                in[0]=in[1]=in[2]=in[3]=0;
            }
        }

        i++;
    }
}

/**
 * encodeblock
 *
 * encode 4 '6-bit' characters into 3 8-bit binary bytes.
 *
 * @param in[]     (unsigned char)
 * @param b64str[] (char)
 * @param len      (int)
 */
void encodeblock(unsigned char in[], char b64str[], int len) {
    unsigned char out[5];

    out[0] = b64[ in[0] >> 2 ];
    out[1] = b64[ ((in[0] & 0x03) << 4) | ((in[1] & 0xf0) >> 4) ];
    out[2] = (unsigned char) (len > 1 ? b64[ ((in[1] & 0x0f) << 2) |
             ((in[2] & 0xc0) >> 6) ] : '=');
    out[3] = (unsigned char) (len > 2 ? b64[ in[2] & 0x3f ] : '=');
    out[4] = '\0';

    strncat(b64str, out, sizeof(out));
}

/**
 * encode
 *
 * name says all. Also adds padding.
 *
 * @param data    (char *)
 * @param encoded (char *)
 */
void encode(char *data, char *encoded) {
    unsigned char in[3];
    int i
      , j = 0
      , len = 0
      ;

    encoded[0] = '\0';

    while(data[j]) {
        len = 0;

        for(i=0; i<3; i++) {
            in[i] = (unsigned char) data[j];

            if(data[j]) {
                j++;
                len++;
            } else {
                in[i] = 0;
            }
        }

        if(len) encodeblock( in, encoded, len );
    }
}

int crypter(int whichWay, char* data, int useQA64) {
    if(!whichWay || !data) exit(1);
    if(!useQA64) useQA64 = 0;
    if(useQA64 == 1) strcpy(b64, qa64);

    char alloc[1024] = "";

    if(whichWay == 0) { // let's say 0 stands for encoding, okay?
        encode(data, alloc);
    } else {
        decode(data, alloc);
    }

    return alloc; // I fucking hate pointers...
}
