function [list_SID fitness_crop_v data] = make_custom_images(atleti_laceno,folder_images,pgvroot,gedcom,flg_download_all)
% automatically download missing images from PhpGedView website
%
% before starting, ensure that:
% 1) the website is updated and aligned to the online content
% 2) the download_pgv_images requirements are satisfied
%
% % es:
% atleti_laceno = '../custom/dati/atleti_laceno.csv';
% folder_images = '../custom/album/custom/';
% pgvroot  = 'http://localhost/work/PhpGedView/' % the actual url is not
%             % important, as CRC is calculated on the graph data (eg localhost/work/PhpGedView
%             % is equal to ars.altervista.org/PhpGedView)
% gedcom = 'caposele';
% flg_download_all = 1; % flag to download all images (the changed ones), not only the missing ones
% clc,tic,[list_SID fitness_crop_v data] = make_custom_images(atleti_laceno,folder_images,pgvroot,gedcom,flg_download_all);toc,


[data str] = read_csv(atleti_laceno);

[v_status_img list_images] = analyse_data(data,str,folder_images,flg_download_all);

% prepare list of SID (cell array of strings)
list_SID = data(list_images,9);

% show preliminary message
disp('Be sure that:')
disp('- Firefox is open and there are no additional message bars (eg. sync or find)')
disp(['- pgv website ' pgvroot ' is aligned to the best-in-class online content'])
disp(['- you logged into the pgv website ' pgvroot])
input('Press ENTER to start downloading...')

% launch the downloader
fitness_crop_v = download_pgv_images(pgvroot,gedcom,list_SID,folder_images);
[temp ind] = sort(-fitness_crop_v);
fitness_crop_v = fitness_crop_v(ind);
list_SID = list_SID(ind);
disp(' ')
disp('Synoptic:')
for i_img = 1:length(list_SID)
    fprintf(1,'%3d) %6s: %.2f\n',i_img,list_SID{i_img},fitness_crop_v(i_img))
end



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
fprintf(1,'\nThere are %d images (on a total of %d: %.0f%%) to be downloaded (%s)...\n\n',length(list_images),size(data,1),length(list_images)/size(data,1)*100,msg)




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
