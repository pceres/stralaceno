#!/bin/bash

# Controlla se index.php esiste nella cartella corrente
if [ ! -f "index.php" ]; then
	echo "Errore: non sei nella root (index.php mancante)."
   	exit 1
fi


# fix file permissions
chmod a+w custom/articoli/*.txt
chmod a+w custom/config/download_cfg.php
chmod a+r custom/config/*.css
chmod a+w custom/contatori/log_contents.php
chmod a+w custom/contatori/lasthitfile.txt
chmod a+w custom/contatori/logfile.txt
chmod a+w custom/contatori/data/data_ars.php
chmod a+w custom/contatori/temp/accs_ars.dat
chmod a+w custom/lotterie/lotteria_*_ans.php
chmod a+w custom/moduli/last_contents/last_contents_cfg.txt

# remove temporary files
if [ -f "custom/config/challenge.php" ]; then
	rm custom/config/challenge.php
fi

# fix folder permission
chmod a+w custom/config
chmod a+w custom/contatori          # backupfile
chmod a+w custom/contatori/back
chmod a+w custom/contatori/temp
