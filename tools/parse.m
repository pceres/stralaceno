% parse logfile.txt

clear

abilita_stima_foto_mancante = 0; % [0,1] a partire da album, id_photo e data prova ad individuare il nome del file della foto

nomefile = 'logfile.txt';
backupfile = 'backupfile*.txt';
% root_path='d:/stralaceno/online_2006_01_17/'; % path della radice del sito
% root_path='/var/www/htdocs/work/ars/'; % path della radice del sito
root_path='/var/www/htdocs/work/stralaceno2/'; % path della radice del sito


% informazioni sul file di log generale
z=dir(nomefile);

% verifica che tutti i backupfile siano confluiti in nomefile
if length(backupfile)
    z0 = dir(backupfile);
    somma=0;
    for i=1:length(z0)
        somma=somma+z0(i).bytes;
    end
    
    if (somma ~= z.bytes)
         error(['Il file di log ' nomefile ' non e'' aggiornato!'])
    end
end


% valuta se rileggere il file di log
must_read = 0;
if ~exist('logfile.mat','file')
    must_read = 1;
else
    load logfile.mat;

    if (~exist('bulk') | ~exist('bytes_read') | ~exist('vseconds') | (z.bytes ~= bytes_read))
        must_read = 1;
    end
end


if must_read

    disp(['Rileggo il file ' nomefile])
    
    fid = fopen(nomefile);

    bulk = {};
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

    % extract data
    label = char(ones(length(bulk),vmax(1))*' ');     % tag della pagina visitata
    arguments = char(ones(length(bulk),vmax(2))*' '); % argomenti passati alla pagina
    ip = char(ones(length(bulk),vmax(3))*' ');        % ip da cui proveniva la richiesta
    referrer = char(ones(length(bulk),vmax(4))*' ');  % indirizzo della pagina da cui proveniva la richiesta
    agent = char(ones(length(bulk),vmax(5))*' ');     % browser utilizzato
    seconds = char(ones(length(bulk),vmax(6))*' ');   % istante della richiesta
    username = char(ones(length(bulk),vmax(7))*' ');  % utente logged in
    vseconds=[];
    
    line_errors = 0;
    for i =1:length(bulk)

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
                ind=find(a2==' ');
                a2 = a2(ind(1)+1:end);
                a2 = strrep(a2,'st','');
                a2 = strrep(a2,'nd','');
                a2 = strrep(a2,'rd','');
                a2 = strrep(a2,'th','');
                a2=strrep(a2,' of','');
                
                tempo=datenum(a2)+(upper(a2(end-1))=='P')*0.5;
            else
                tempo = str2num(vks{6})/(60*60*24)+datenum('1 January 1970 12:00:00 AM');
            end
            vseconds = [vseconds;tempo];

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
%             zz = ((data==repmat(data(1,:),size(data,1),1))')
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
    
    %
    % filtri sulle classifiche:
    %
    
    % classifica sulle sole foto (di cui si sa il file)
    if (strcmp(data_list{data_i},'label_arguments'))
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
                
                classifica_foto{end+1} = {num,album,foto};
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

                    conteggiofoto=classifica_foto{somma}{1};
                    albumfoto=classifica_foto{somma}{2};
                    nomefoto=classifica_foto{somma}{3};

                    fotoname=[root_path 'custom/album/' albumfoto '/' nomefoto];
                    matr=imread(fotoname);
                    image(matr);
                    title([albumfoto ' - ' nomefoto ' : ' num2str(conteggiofoto)],'interpreter','none')
                    axis off;
                    axis image;
                    axis tight;
                end
            end
        end
        
    end % fine classifica foto

end

zz=[num2str(v',3) ones(length(v),1)*' ' list_data];

ago = 'Linux'
for i = 1:size(zz,1),if ~isempty(findstr(ago,zz(i,:))),disp(zz(i,:)),end,end

ago = 'NT4'
for i = 1:size(zz,1),if ~isempty(findstr(ago,zz(i,:))),disp(zz(i,:)),end,end


% grafico accessi pagine
figure,subplot(1,1,1),hold off
hist(fix(vseconds),round(vseconds(end)-vseconds(1)));
vett_num = hist(fix(vseconds),round(vseconds(end)-vseconds(1)));zz=datestr(unique(fix(vseconds)));ind=round(1:((size(zz,1)-1)/6):size(zz,1));set(gca,'xticklabel',zz(ind,:),'xtick',datenum(zz(ind,:)),'xgrid','on')
vett_x = unique(fix(vseconds))-1;
hold on;plot([1 1]*datenum('2 september 2005'),get(gca,'Ylim'),'r')

[y,m] = datevec(min(vseconds));
[y2,m2] = datevec(max(vseconds));
hold on,for i=m:m2,plot([1 1]*datenum(y,i,1),get(gca,'Ylim'),'g'),end

% record di accessi alle pagine
max_pages = 200;
ind=find(vett_num>max_pages);
% ind=ind(1:end);
[temp, ind2]=sort(-vett_num(ind));ind=ind(ind2);
disp(' ')
disp(['Giorni in cui ci sono state piu'' di ' num2str(max_pages) ' pagine visitate:'])
[datestr(vett_x(ind)) repmat(' (',length(ind),1) num2str(vett_num(ind)') repmat(' accessi)',length(ind),1) ]


% ripartizione per giorni della settimana
tag='homepage'
w=zeros(7,1);zz=datestr(vseconds,'ddd');zzu=unique(zz,'rows');zzu=zzu([2 6 7 5 1 3 4],:);for i=1:size(zzu,1),for ii = 1:size(zz,1);if (strcmp(zz(ii,:),zzu(i,:)) & strfind(label(ii,:),tag)),w(i)=w(i)+1;end,end,end
[zzu repmat(' ',length(w),1) num2str(w)]
figure,bar(w);set(gca,'XTickLabel',zzu);title(tag)
