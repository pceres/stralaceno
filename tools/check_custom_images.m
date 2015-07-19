% analizza gli ID genealogici presenti in tempi_laceno.csv, incrociandoli
% con le immagini presenti nell'album "custom"

clc

filename = '/home/ceres/Desktop/siti/stralaceno/custom/dati/atleti_laceno.csv';
img_folder = '/home/ceres/Desktop/siti/stralaceno/custom/album/custom/';
ind_id = 1;
ind_nome = 2;
ind_id_genealogico = 9;

fid = fopen(filename, 'r');
text = fread(fid, 1e6, 'uint8=>char')';
fclose(fid);

list = regexp(text,'[^\r\n]+','match')';

z = dir([img_folder '*.jpg']);
list_img = {};
for i_img=1:length(z);
    img = z(i_img).name;
    list_img(end+1,:)={img 0}; %#ok<AGROW>
end

matr={};
for i_list=1:length(list)
    line=list{i_list};
    z=regexp(line,'[^;]+','match');
    matr(end+1,:)=z; %#ok<AGROW>
end

ind_img = find(~strcmp(matr(2:end,ind_id_genealogico),'-'))+1;
for i_img = 1:length(ind_img)
    vett = matr(ind_img(i_img),:);
    id=vett{ind_id};
    nome=vett{ind_nome};
    img=vett{ind_id_genealogico};
    
    ind = strmatch([img '.jpg'],list_img(:,1),'exact');
    if ~isempty(ind)
        msg = 'ok';
        list_img{ind,2} = 1; %#ok<AGROW>
    else
        msg = '*** MISSING!';
    end
    
    fprintf(1,'%3s %30s: %5s %s\n',id,nome,img,msg)
end

ind_spare = find(~cell2mat(list_img(:,2)));
if ~isempty(ind_spare)
    fprintf(1,'\n\n')
    for i_img = 1:length(ind_spare);
        img = list_img{ind_spare(i_img)};
        fprintf(1,'*** %s unused!\n',img)
    end
end


