function n2rn(path)

%addpath('/var/www/htdocs/work/stralaceno2/tools/')


if ~exist(path,'dir')
    dofile(path)
    disp('Fatto!')
    return
else
    path
end

oldpath=cd(path);

z=dir('.');
for i=1:length(z)

    name = z(i).name;
    
    if isdir(name)
        if ( (name(1) ~= '.') & (~strcmp('CVS',upper(name))) )
            n2rn([path '/' name]);
        end
    else
       switch (lower(name((end-1):end)))
            case {'.m'}
                dofile(name)
       end
       switch (lower(name((end-3):end)))
            case {'.php','.txt','.cfg','.tpl'}
                dofile(name)
        end
    end

end

cd(oldpath);


%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function dofile(name)

name2 = [name '.bak'];

disp(['  ' name])

fid=fopen(name);
fid2=fopen(name2,'w');
while ~feof(fid)
    ks=fgetl(fid);
    %disp(ks)
    fwrite(fid2,[ks char([13 10])]);
end

fclose(fid);
fclose(fid2);

delete(name);
copyfile(name2,name);
delete(name2);
