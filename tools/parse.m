% portare il path nella cartella contenente i backupfile*.txt

clear

abilita_stima_foto_mancante = 0; % [0,1] a partire da album, id_photo e data prova ad individuare il nome del file della foto

nomefile = 'logfile.txt';
backupfile = 'backupfile*.txt';

% path della radice del sito
% root_path='/var/www/htdocs/work/stralaceno2/';
root_path='/var/www/htdocs/work/ars/';

% archivio foto cancellate
% deleted_photos_path = '/mnt/win_d/stralaceno/statistiche/archivio_foto_cancellate/';
deleted_photos_path = '/mnt/win_d/stralaceno/statistiche_ars/archivio_foto_cancellate/';

% elenco date notevoli
% date_notevoli = {...
% 'Stralaceno 2005',	'2 september 2005'	;...
% 'Stralaceno 2006',	'30 august 2006'	;...
% };
date_notevoli = {...
'fine giocate sondaggio mondiali 2006'		,'8 june 2006'		;...
'fine sondaggio mondiali 2006'			,'11 july 2006'		;...
'apertura giocate sondaggio champions 06/07'	,'20 december 2006'	;...
};



% informazioni sul file di log generale
z=dir(nomefile);
if isempty(z)
    z = struct('bytes',0);
end

% verifica che tutti i backupfile siano confluiti in nomefile
if ~isempty(backupfile)
    z0 = dir(backupfile);
    somma=0;
    for i=1:length(z0)
        disp(sprintf('%15s) %d',z0(i).name,z0(i).bytes));
        somma=somma+z0(i).bytes;
    end

    if (somma ~= z.bytes)
        disp(sprintf('Il file di log %s non e'' aggiornato (%d bytes invece di %d). Lo ricostruisco...',nomefile,z.bytes,somma))

        bulk='';ancora=1;i=0;
        while ancora,name=sprintf('backupfile%03d.txt',i);
                if exist(name,'file')
                        fid=fopen(name);
                        a=char(fread(fid,'char')');
                        bulk=[bulk a];
                        fclose(fid);i=i+1;
                else
                        ancora=0;
                end
        end
        fid=fopen('logfile.txt','w');
        fwrite(fid,bulk);
        fclose(fid);

        disp(['    Fatto: ' num2str(length(bulk)) ' bytes.'])
        disp(' ')

    end
end


% valuta se rileggere il file di log
must_read = 0;
if ~exist('logfile.mat','file')
    must_read = 1;
else
    load logfile.mat;

    if (~exist('bulk','var') | ~exist('bytes_read') | ~exist('vseconds') | (z.bytes ~= bytes_read))
        must_read = 1;
    end
end


if must_read

    disp(['Rileggo il file ' nomefile])

    fid = fopen(nomefile);


    % leggi da logfile.txt tutte le righe gia' presenti in bulk (letto da logfile.txt)
    if exist('bulk','var')
        count=0;
        while (count<length(bulk)),
            count = count+1;
            fgetl(fid);
        end

        disp(['Comincio a leggere ' nomefile ' scartando le prime ' num2str(count) ' righe (gia'' presenti in bulk).'])
        disp(' ')

    else
        disp(['Devo leggere ' nomefile ' da zero.'])
        disp(' ')

        bulk = {};
    end

    vmax=zeros(1,7); % sette campi per linea
    while 1
        tline = fgetl(fid);
        if ~ischar(tline),
            break,
        end

        ind = findstr(tline,'::');
        ind2=ind([1 find(([ind(2:end-1)-ind(1:end-2)]~=1)|([ind(3:end)-ind(2:end-1)]~=1))+1 length(ind)]);

        % parse di ogni singola linea; il formato della linea e' 
        % <label>::<arguments>::<ip>::<referer>::<agent>::<date>::<username>
        vks = {};
        p=1;
        for i =1:length(ind2)
            vks{i} = tline(p:(ind2(i)-1));
            p=(ind2(i)+2);

            if isempty(vmax)
                vmax=zeros(1,length(ind2)+1);
            end
            if (vmax(i) < length(vks{i}))
                vmax(i) = length(vks{i});
            end

        end
        if (p <=length(tline))
            vks{i+1} = tline(p:end);

            if (vmax(i+1) < length(vks{i+1}))
                vmax(i+1) = length(vks{i+1});
            end
        end

        %
        % correzioni per riempire eventuali campi assenti
        %

        % username mancante
        if (length(vks)==6) % la linea e' stata scritta quando non veniva ancora loggato lo username
            vks{7} = '-';
        end

        % nome foto mancante
        if (abilita_stima_foto_mancante & strcmp(vks{1},'foto') & all(vks{2}~='(')) % manca il nome della foto, prova ad aggiungerlo
            ks = vks{2};

            ind1=find(ks == '&');
            ind2=find(ks == '=');
            id_photo_x=str2num(ks(ind2(1)+1:ind1-1));
            album_x=ks(ind2(2)+1:end);

            % individua il tempo numerico
            if isempty(str2num(vks{6}))
                a2 = vks{6};ind=find(a2==' ');a2 = a2(ind(1)+1:end);a2 = strrep(a2,'st','');a2 = strrep(a2,'nd','');a2 = strrep(a2,'rd','');a2 = strrep(a2,'th','');a2=strrep(a2,' of','');
                tempo=datenum(a2)+(upper(a2(end-1))=='P')*0.5;
            else
                tempo = str2num(vks{6})/(60*60*24)+datenum('1 January 1970 12:00:00 AM');
            end


            [nome dati]=get_photo_name(tempo,album_x,id_photo_x);

            if ~isempty(nome) % se e' riuscito ad individuare la foto, aggiungila in vks{2}
                vks{2} = [vks{2} '(' nome ')'];
            end

        end

        bulk{end+1} = vks;

    end

    fclose(fid);



    disp(['Ho letto il file ' nomefile '. Ora lo elaboro:'])

    if exist('ip')
        start = size(ip,1);
    else
        start = 0;

        % inizializza extract data
        label = [];     % tag della pagina visitata
        arguments = []; % argomenti passati alla pagina
        ip = [];        % ip da cui proveniva la richiesta
        referrer = [];  % indirizzo della pagina da cui proveniva la richiesta
        agent = [];     % browser utilizzato
        seconds = [];   % istante della richiesta
        username = [];  % utente logged in
        vseconds=[];
    end

    delta = length(bulk)-start;	% numero di campi vuoti (da elaborare) da aggiungere

    % extract data
    label     = strvcat(label, char(ones(delta,vmax(1))*' '));     % tag della pagina visitata
    arguments = strvcat(arguments, char(ones(delta,vmax(2))*' ')); % argomenti passati alla pagina
    ip        = strvcat(ip, char(ones(delta,vmax(3))*' '));        % ip da cui proveniva la richiesta
    referrer  = strvcat(referrer, char(ones(delta,vmax(4))*' '));  % indirizzo della pagina da cui proveniva la richiesta
    agent     = strvcat(agent, char(ones(delta,vmax(5))*' '));     % browser utilizzato
    seconds   = strvcat(seconds, char(ones(delta,vmax(6))*' '));   % istante della richiesta
    username  = strvcat(username, char(ones(delta,vmax(7))*' '));  % utente logged in
    vseconds  = [vseconds];

    start = start+1;
    disp(['    ...partendo dalla riga ' num2str(start)])



    line_errors = 0;
    for i =start:length(bulk)

        vks = bulk{i};
        if length(vks) == 7 % il formato gestito contiene 7 campi

            % label
            label(i,1:length(vks{1})) = vks{1};

            % argomenti
            if (~isempty(vks{2}))
                arguments(i,1:length(vks{2})) = vks{2};
            end

            % ip
            ip(i,1:length(vks{3})) = vks{3};

            % referrer
            referrer(i,1:length(vks{4})) = vks{4};

            % agent
            agent(i,1:length(vks{5})) = vks{5};

            % vseconds (vettore numerico)
            if isempty(str2num(vks{6}))
                a2 = vks{6};
                tempo = datenum(regexprep(a2,'[A-Za-z]+ ([0-9]{2})[a-z]{2} of ([A-Za-z]+) ([0-9]{4})','$1 $2 $3'));
            else
                tempo = str2num(vks{6})/(60*60*24)+datenum('1 January 1970 12:00:00 AM');
            end
            vseconds(i) = tempo;

            % seconds
            seconds(i,1:length(vks{6})) = vks{6};

            % username
            username(i,1:length(vks{7})) = vks{7};

        else
            disp(['riga ' num2str(i) ' : '])
            vks{:}
            line_errors = line_errors+1
        end


        if (mod(i,500)==0)
            disp(['   ' vks{6}]) % ogni tanto visualizza il tempo della linea sotto elaborazione
        end

    end

    bytes_read = z.bytes;
    must_read = 0;
    save logfile bulk label arguments ip referrer agent seconds username vseconds bytes_read
end


% etichette delle tabelle da ordinare e visualizzare
data_list = {...
    'label',...
    'label_arguments',...
    'ip',...
    'referrer',...
    'agent',...
    'username',...
    };

for data_i = 1:length(data_list)
    % determina la matrice da analizzare
    switch data_list{data_i}
        case 'label'
            data = label;
        case 'label_arguments'
            data = [label ones(size(label,1),1)*'-' arguments];
        case 'ip'
            data = ip;
        case 'referrer'
            data = referrer;
        case 'agent'
            data = agent;
        case 'username'
            data = username;
        otherwise
            error(['Data_list sconosciuto: ' data_list{data_i}])
    end

    v=[];
    list_data='';
    while (size(data,1)>0)
        if (prod(size(data))>3e6)
            v_=[];
            for temp_i=1:size(data,1)
                v_(temp_i)=strcmp(data(temp_i,:),data(1,:));
            end
        else

            zz=((data==repmat(data(1,:),size(data,1),1))');
            if (size(zz,1) == 1)
                zz=[zz;zeros(1,size(zz,2))];
            end
            v_ = sum(zz)==size(data,2);
        end
        v(end+1)=sum(v_);
        list_data=strvcat(list_data,data(1,:));
        data=data(find(~v_),:);
    end
    [temp j]=sort(-v);

    disp(' ')
    disp(' ')
    disp([data_list{data_i} ' :'])
    disp(' ')
    disp([num2str(v(j)','%5d : '),list_data(j,:)])




    switch data_list{data_i}
    case 'label_arguments'

        %
        % filtri sulle classifiche:
        %
        filtro_album = '';	% nome dell'album di cui si vuole avere la classifica

        % classifica sulle sole foto (di cui si sa il file)
        disp(' ')
        disp('Classifica foto piu'' viste: ')
        classifica_foto={};
        for i = 1:length(v)
            num=v(j(i));
            dato=list_data(j(i),:);
            if (strfind(dato,'(') & strfind(dato,'id_photo'))
                ind1=find(dato=='(');
                ind2=find(dato==')');
                ind3=find(dato=='&');
                album=dato((ind3+7):(ind1-1));
                foto=dato((ind1+1):(ind2-1));
                disp([num2str(num) ': album ' album ', foto ' foto])

                if (isempty(filtro_album) | strcmp(album,filtro_album))
                    classifica_foto{end+1} = {num,album,foto};
                end
            end
        end

        % crea la figura con tutte le foto (se root_path punta alla radice del sito)
        if (~isempty(root_path) & ~isempty(classifica_foto))
            h=figure;
            set(h,'numbertitle','off','name',['Classifica foto piu'' viste aggiornata al ' datestr(max(vseconds))]);
            m=3;n=3;
            for i=1:m
                for j=1:n
                    somma=(i-1)*n+j;
                    subplot(m,n,somma);

                    if (somma <= length(classifica_foto))

                        conteggiofoto=classifica_foto{somma}{1};
                        albumfoto=classifica_foto{somma}{2};
                        nomefoto=classifica_foto{somma}{3};

                        matr = [];
                        fotoname=[root_path 'custom/album/' albumfoto '/' nomefoto];
                        if exist(fotoname,'file')
                                matr=imread(fotoname);
                        else
                                fotoname2=[deleted_photos_path albumfoto '/' nomefoto];
                                if exist(fotoname2,'file')
                                        matr=imread(fotoname2);
                                        disp(['La foto non esiste piu'', la prendo da ' fotoname2])
                                else
                                        disp([fotoname])
                                end
                        end
                        if (~isempty(matr))
                                image(matr);
                        end
                        title([albumfoto ' - ' nomefoto ' : ' num2str(conteggiofoto)],'interpreter','none')
                        axis off;
                        axis image;
                        axis tight;
                    end
                end
            end
        end

    %end % fine classifica foto



    % classifica sistemi operativi
    case 'agent'

        zz=[num2str(v',3) ones(length(v),1)*' ' list_data];

        ago = 'Linux'
        for i = 1:size(zz,1),if ~isempty(findstr(ago,zz(i,:))),disp(zz(i,:)),end,end

        ago = 'Windows NT'
        for i = 1:size(zz,1),if ~isempty(findstr(ago,zz(i,:))),disp(zz(i,:)),end,end



    end % switch data_list{data_i}



end


% grafico accessi pagine
figure,subplot(1,1,1),hold off
vett_x=floor(vseconds(1)):ceil(vseconds(end));vett_num=histc(vseconds,vett_x);

hold on;
zz=datevec(vett_x);

ind=find(rem(zz(:,2),2)==0);bar(vett_x(ind),vett_num(ind),1,'g'); % mesi pari
ind=find(rem(zz(:,2),2)==1);bar(vett_x(ind),vett_num(ind),1,'b'); % mesi dispari
ind=strmatch('Sun',datestr(vett_x,'ddd'));bar(vett_x(ind),max(0,vett_num(ind)-0.1),0.8/7,'r'); % domeniche

zz=datestr(unique(fix(vseconds)));
ind=round(1:((size(zz,1)-1)/8):size(zz,1));
set(gca,'xticklabel',zz(ind,:),'xtick',datenum(zz(ind,:)),'xgrid','on')
% righe segnadata verticali:
for i_data = 1:size(date_notevoli,1)
        x_data = datenum(date_notevoli{i_data,2});
        estremi_y = get(gca,'Ylim');
        hold on;plot([1 1]*x_data,estremi_y,'r'),text(x_data,min(estremi_y)+diff(estremi_y)*(1-0.05*i_data),date_notevoli{i_data,1});
end

[y,m] = datevec(min(vseconds));
[y2,m2] = datevec(max(vseconds));
hold on,for i=m:m2,plot([1 1]*datenum(y,i,1),get(gca,'Ylim'),'g'),end

% record di accessi alle pagine
max_pages = 200;
ind=find(vett_num>max_pages);
if ~isempty(ind)
        [temp, ind2]=sort(-vett_num(ind));ind=ind(ind2);
        disp(' ')
        disp(['Giorni in cui ci sono state piu'' di ' num2str(max_pages) ' pagine visitate:'])
        disp(' ')
        disp([datestr(vett_x(ind),'dd-mmm-yyyy') repmat(' (',length(ind),1) num2str(vett_num(ind)') repmat(' accessi)',length(ind),1) ])
        disp(' ')
else
        disp(' ')
        disp(['Non c''e'' nemmeno un giorno in cui ci sono state piu'' di ' num2str(max_pages) ' pagine visitate:'])
end


% ripartizione per giorni della settimana
tag='homepage'
w=zeros(7,1);zz=datestr(vseconds,'ddd');zzu=unique(zz,'rows');zzu=zzu([2 6 7 5 1 3 4],:);for i=1:size(zzu,1),for ii = 1:size(zz,1);if (strcmp(zz(ii,:),zzu(i,:)) & strfind(label(ii,:),tag)),w(i)=w(i)+1;end,end,end
[zzu repmat(' ',length(w),1) num2str(w)]
figure,bar(w);set(gca,'XTickLabel',zzu);title(tag)




%
% individua le pagine in uscita
%

% ip=ip(1:10,:);
% label=label(1:10,:);
% vseconds=vseconds(1:10);

if (0)
        disp('individua le pagine in uscita...')
        pause

        parse3
end
