#include <unistd.h>
#include "crypter.h"

int main(int argc, char* argv[]) {
    int whichWay
      , useQA64
      ;

    if(argc != 3) return 1;
    if(argv[1] == "\0") return 2;
    if(argv[2] == "\0") return 2;
    if(argv[3] == "\0") return 2;

    whichWay = atoi(argv[1]);

    char* data = strdup(argv[2]);

    if(argv[4] != "\0") {
        useQA64 = atoi(argv[3]);
    } else {
        useQA64 = 0;
    }

    char crypted = *((char*) crypter(whichWay, data, useQA64));
    /** SEGMENTATION FAILT
     * because pointers hate me... I am sure it worked a while ago, but then
     * again, I might have been on acid again...
     */

    printf("%s\n", crypted);

    crypted = "";
    data = "";

    return 0;
}
