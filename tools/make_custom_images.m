function make_custom_images
% automatically download missing images from PhpGedView website
%
% % es:
% clc,make_custom_images;

atleti_laceno = '../custom/dati/atleti_laceno.csv';
folder_images = '../custom/album/custom/';
pgvroot  = 'http://ars.altervista.org/PhpGedView/';
pgvroot  = 'http://localhost/work/PhpGedView/'
gedcom = 'caposele';
flg_download_all = 1; % flag to download all images, not only the missing ones

[data str] = read_csv(atleti_laceno);

[v_status_img list_images] = analyse_data(data,str,folder_images,flg_download_all);

% prepare list of SID (cell array of strings)
list_SID = data(list_images,9);

% show preliminary message
disp('Be sure that:')
disp('- Firefox is open and there are no additional message bars (eg. sync or find)')
disp(['- you logged into the pgv website ' pgvroot])
input('Press ENTER to start downloading...')

% launch the downloader
download_pgv_images(pgvroot,gedcom,list_SID,folder_images)



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function [v_status_img list_images] = analyse_data(data,str,folder_images,flg_download_all)

v_status_img = ones(size(data,1),1); % default: no id_geneo
list_images = []; % list of athletes that need an image
for i_atl = 1:size(data,1)
    id       = data{i_atl,str.ind_id};
    name     = data{i_atl,str.ind_name};
    %image    = data{i_atl,str.ind_image};
    id_genea = data{i_atl,str.ind_id_genea};
    
    if ~isempty(id_genea) && ~strcmp(id_genea,'-')
        filename_image = [folder_images id_genea '.jpg'];
        if exist(filename_image,'file')
            status_img = 2; % image already exists
            msg_img = ' Ok';
            if flg_download_all
                list_images(end+1) = i_atl; %#ok<AGROW>
            end
        else
            status_img = 0; % missing image
            msg_img = '...';
            list_images(end+1) = i_atl; %#ok<AGROW>
        end
        v_status_img(i_atl) = status_img;
        
        fprintf(1,'%3s: %-30s : %6s %s\n',id,name,id_genea,msg_img)
    end
end

if flg_download_all
    msg = 'old and new';
else
    msg = 'only new';
end
fprintf(1,'\nThere are %d images to download (%s)...\n\n',length(list_images),msg)




%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function [data str header] = read_csv(filename)

fid = fopen(filename);
data0 = textscan(fid,'%s','Delimiter',';');
fclose(fid);

data = {};
for i=1:9
    data(:,i) = data0{1}(i:9:end); %#ok<AGROW>
end

header = data(1,:);
data   = data(2:end,:);

str.ind_id   = 1;
str.ind_name = 2;
str.ind_link = 7;
str.ind_image = 8;
str.ind_id_genea = 9;
