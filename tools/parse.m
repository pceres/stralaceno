% parse logfile.txt

clear

nomefile = 'logfile.txt';
backupfile = 'backupfile*.txt';
% root_path='d:/stralaceno/online_2006_01_17/'; % path della radice del sito
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
    vmax=[];
    while 1
        tline = fgetl(fid);
        if ~ischar(tline),
            break,
        end

        ind = findstr(tline,'::');
        ind2=ind([1 find(([ind(2:end-1)-ind(1:end-2)]~=1)|([ind(3:end)-ind(2:end-1)]~=1))+1 length(ind)]);

        % parse
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
    vseconds=[];

    line_errors = 0;
    for i =1:length(bulk)

        vks = bulk{i};
        if length(vks) == 6
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

        else
            disp(['riga ' num2str(i) ' : '])
            vks{:}
            line_errors = line_errors+1
        end
        
        
        if (mod(i,500)==0)
            disp(['   ' vks{end}])
        end
        
    end

    bytes_read = z.bytes;
    must_read = 0;
    save logfile bulk label arguments ip referrer agent seconds vseconds bytes_read
end


% visualizza
data_list = {...
    label,...
    [label ones(size(label,1),1)*'-' arguments],...
    ip,...
    referrer,...
    agent,...
    };

for data_i = 1:length(data_list)
    data = data_list{data_i};

    list_data = unique(data,'rows');
    v = zeros(1,size(list_data,1));

    for i = 1:size(list_data,1)
        for ii = 1:size(data,1);
            if strcmp(data(ii,:),list_data(i,:))
                v(i)=v(i)+1;
            end
        end
    end

    disp(' ')
    disp(' ')

    [temp,j]=sort(-v);
    for i = 1:length(v);
        disp([num2str(v(j(i))) ': ' list_data(j(i),:)])
    end
    
    %
    % filtri sulle classifiche:
    %
    
    % classifica sulle sole foto (di cui si sa il file)
    if (data_i==2)
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
        if ~isempty(root_path)
            h=figure;
            set(h,'numbertitle','off','name','Classifica foto piu'' viste');
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
w=zeros(7,1);zz=datestr(vseconds,'dddd');zzu=unique(zz,'rows');zzu=zzu([2 6 7 5 1 3 4],:);for i=1:size(zzu,1),for ii = 1:size(zz,1);if (strcmp(zz(ii,:),zzu(i,:)) & strfind(label(ii,:),tag)),w(i)=w(i)+1;end,end,end
[zzu repmat(' ',length(w),1) num2str(w)]
figure,bar(w);set(gca,'XTickLabel',zzu);title(tag)
