[help_config]
Formato [elenco_forums]:
<forum_id>::<forum_caption>::<forum_description>::<forum_status>::<forum_read_groups>::<forum_write_groups>::<forum_auth_mode>::<forum_topics>::<forum_last_post>
  <forum_id> : numero incrementale che identifica il forum
 <forum_caption> : titolo del forum
 <forum_description> : descrizione del forum
 <forum_status> : stato del forum:
     open   : il forum e' aperto, si puo' modificare
     closed : il forum e' chiuso, e' in sola lettura
     hidden : il forum e' nascosto, non puo' essere nemmeno letto
 <forum_read_groups> : gruppi abilitati a leggere il forum
 <forum_write_groups> : gruppi abilitati a scrivere sul forum
 <forum_auth_mode> : nick dell'autore del post:
      anonimous  : si puo' scrivere qualsiasi nick
      logged : il nick e' lo username, ed e' bloccato
 <forum_topics> : numero di topics presenti nel forum
 <forum_last_post> : <id_topic>,<id_post> dell'ultimo messaggio postato

[elenco_forums]
0::Il forum di ArsWeb::Uno spazio di discussione libera, aperta a tutti::open::::::anonimous::567::10,21
1::Il forum privato dell'ARS Amatori Running Sele::Spazio di discussione libera riservato ai soci ARS::open::soci_ars::soci_ars::anonimous::20::0,17
