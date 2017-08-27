function verify_unique_id
% cross check data files to verify id's are not duplicated, names are
% correctly linked from tempi_laceno to dati_laceno, etc.
%


atleti_laceno        = '../custom/dati/atleti_laceno.csv';
tempi_laceno         = '../custom/dati/tempi_laceno.csv';
organizzatori_laceno = '../custom/dati/organizzatori_laceno.csv';

[data_atl header_atl] = read_csv(atleti_laceno,9);
[data_tem header_tem] = read_csv(tempi_laceno,6);
[data_org header_org] = read_csv(organizzatori_laceno,7);

fprintf(1,'\n\n\n')
fprintf(1,'\n- check IDs are ordered:\n')
check_ordered_IDs(data_atl(:,1),'atleti_laceno');

fprintf(1,'\n- check no gap (eg. 1,2,3,..,80, but no ID 45):\n')
check_no_gap_IDs(data_atl(:,1),'atleti_laceno');
check_no_gap_IDs(data_org(:,1),'organizzatori_laceno');
check_no_gap_IDs(data_tem(:,1),'tempi_laceno');

fprintf(1,'\n- check IDs are not duplicated:\n')
check_unique_IDs(data_atl(:,1),data_atl(:,2),'atleti_laceno');
check_unique_IDs(data_tem(:,1),data_tem(:,2),'tempi_laceno');
check_unique_IDs(data_org(:,1),data_org(:,2),'organizzatori_laceno');

fprintf(1,'\n- cross check name of athletes from atleti_laceno table\n')
check_cross_IDs(data_atl(:,1),data_atl(:,2),data_tem(:,1),data_tem(:,2),'tempi_laceno','atleti_laceno');



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function ok = check_cross_IDs(vett_ID_atl,vett_nome_atl,vett_ID_tem,vett_nome_tem,tag,tag_ref)
% cross check name of athletes from atleti_laceno table

ok = 1;
for i=1:length(vett_ID_tem)
    ID_tem   = vett_ID_tem{i};
    nome_tem = vett_nome_tem{i};
    
    ind = strmatch(ID_tem,vett_ID_atl,'exact');
    nome_atl = vett_nome_atl{ind};
    
    if ( ~isequal(nome_tem,nome_atl) )
        ok = 0;
        fprintf(1,'\t\t*** ERRORE: ID %s associato a nome errato: %s invece di %s\n',ID_tem,nome_tem,nome_atl)
    end
end

if ok
    fprintf(1,'Ok: nel file "%s" i nomi sono correttamente riportati dal file "%s".\n',tag,tag_ref)
else
    fprintf(1,'*** ERRORE: nel file "%s" alcuni nomi non sono riportati correttamente dal file "%s"!\n',tag,tag_ref)
end



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function ok = check_no_gap_IDs(vett_IDs,tag)
% check no gap (eg. 1,2,3,..,80, but no ID 45

vett=str2double(vett_IDs);

gap = setdiff(1:max(vett),vett);

if isempty(gap)
    ok = 1;
    fprintf(1,'Ok: nel file "%s" nessun ID è stato saltato da 1 a %d.\n',tag,max(vett))
else
    ok = 0;
    fprintf(1,'*** ERRORE: nel file "%s" sono stati saltati alcuni ID (%s)!\n',tag,num2str(gap,'%d,'))
end



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function ok = check_ordered_IDs(vett_IDs,tag)
% check ID's are ordered

vett=str2double(vett_IDs);
vett_ok=(1:max(vett))';
ok = isequal(vett,vett_ok);

if ok
    fprintf(1,'Ok: gli ID nel file "%s" sono ordinati.\n',tag)
else
    fprintf(1,'*** ERRORE: gli ID nel file "%s" NON sono ordinati!\n',tag)
end



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function ok = check_unique_IDs(vett_IDs,vett_name,tag)
% check ID's are not duplicated

ok = 1;
vett_flg = zeros(size(vett_IDs));
for i=1:length(vett_IDs)
    if ~vett_flg(i)
        ID = vett_IDs{i};
        ind = strmatch(ID,vett_IDs,'exact');
        vett_flg(ind)=1;
        vett_names = unique(vett_name(ind));
        if ( length(vett_names)>1 )
            ok = 0;
            ks_nomi = sprintf('%s,',vett_names{:});
            fprintf(1,'\t\t*** ERRORE: ID %s associato a più di un nome:%s\n',ID,ks_nomi)
        end
    end
end
if ok
    disp(['Ok: file ' tag ' con gli ID non duplicati.'])
else
    disp(['*** ERRORE: file ' tag ' con gli ID duplicati!'])
end



%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function [data header] = read_csv(filename,num_col)

fid = fopen(filename);
data0 = textscan(fid,'%s','Delimiter',sprintf('\n'));
fclose(fid);

z=regexp(data0{1},';','split');
data = reshape([z{:}],num_col,length(z))';

header = data(1,:);
data   = data(2:end,:);
