#!/bin/bash

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
rm custom/config/challenge.php

# fix folder permission
chmod a+w custom/config
chmod a+w custom/contatori          # backupfile
chmod a+w custom/contatori/back
chmod a+w custom/contatori/temp
