function make_custom_images
% automatically download missing images from PhpGedView website
%
% % es:
% clc,make_custom_images;

atleti_laceno = '../custom/dati/atleti_laceno.csv';
folder_images = '../custom/album/custom/';
url_format  = 'http://ars.altervista.org/PhpGedView/treenav.php?ged=caposele&rootid=<PID>';
flg_download_all = 1; % flag to download all images, not only the missing ones

[data str] = read_csv(atleti_laceno);

[v_status_img list_images] = analyse_data(data,str,folder_images,flg_download_all);

prepare_images(data,str,list_images,url_format)



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function javaimg = prepare_images(data,str,list_images,url_format)

dest_folder = 'snapshot';

robot = robot_wrapper('init');

screenSize = get(0, 'screensize');
width  = screenSize(3);
height = screenSize(4);

% give focus to the browser
robot_wrapper('mouse_move',{robot,width*0.20, height*0.105});
robot_wrapper('mouse_click',{robot,'left'});
pause(0.3)

if ~exist(dest_folder,'dir')
    mkdir(dest_folder)
end

for i_atl = list_images
    id       = data{i_atl,str.ind_id};
    name     = data{i_atl,str.ind_name};
    % image    = data{i_atl,str.ind_image};
    id_genea = data{i_atl,str.ind_id_genea};
    
    url = strrep(url_format,'<PID>',id_genea);
    
    % give focus to the browser url control
    robot_wrapper('mouse_move',{robot,width*0.20, height*0.105});
    robot_wrapper('mouse_click',{robot,'left'});
    pause(0.2)
    robot_wrapper('key_press',{robot,'^(a)'}); % select all
    pause(0.2)
    robot_wrapper('key_press',{robot,url}); % type the url
    pause(0.2)
    robot_wrapper('key_press',{robot,sprintf('\n')}); % enter
    pause(2) % wait for page load
    
    % scroll the window
    robot_wrapper('mouse_move',{robot,width*0.995, height*0.925});
    for i_tmp = 1:4
        robot_wrapper('mouse_click',{robot,'left'});
        pause(0.2)
    end
    
    % move graph a bit to the right
    robot_wrapper('mouse_move',{robot,width*0.01, height*0.47})
    pause(0.2)
    robot_wrapper('mouse_move_with_button_pressed',{robot,'left',width*0.055, height*0.47})
    
    
    %%input('Please position the graph in the middle of the screen, then press ENTER','s')
    
    filename = [dest_folder filesep id_genea '.jpg'];
    javaimg = robot_wrapper('save_snapshot',{robot,filename});
    
    fprintf(1,'%3s: %-30s : %6s %s\n',id,name,id_genea,url)
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
