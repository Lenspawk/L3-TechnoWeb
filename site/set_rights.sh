#!/bin/bash

if [ "$0" == "bash" ]
then
    echo ne lancez pas la commande avec \"source\"
    echo lancez la comme une commande externe
    return
fi

if [ ! -d var ]
then
    echo répertoire "var" inexistant
    echo placez-vous à la racine du projet
    exit 1
fi

setfacl -R -m u:"www-data":rwX -m u:${USER}:rwX var
if [ $? -ne 0 ]
then
    echo erreur \"setfacl -R\"
    exit 1
fi
setfacl -dR -m u:"www-data":rwX -m u:${USER}:rwX var
if [ $? -ne 0 ]
then
    echo erreur \"setfacl -dR\"
    exit 1
fi

exit 0
