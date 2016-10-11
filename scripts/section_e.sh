#!/bin/sh

# Related to #7

echo "ID\tLOCAL\tOFFICIAL" 

for a in $(cat s.txt | sed -e "s/\"//g;"); do
    ID=$(echo $a)
    OFFICIAL=$(curl -s "http://rollerderbytestomatic.com/api/0.2/xml/question?developer=Octplane&application=FrenchRDTOMDataimporter&ID=$ID" | xmllint --xpath "//section/text()"  -)
    LOCAL=$(curl -s "http://localhost:20080/api/0.2/xml/question?developer=Octplane&application=FrenchRDTOMDataimporter&ID=$ID" | xmllint --xpath "//section/text()"  -) 

    echo "$ID\t$LOCAL\t$OFFICIAL"
done
