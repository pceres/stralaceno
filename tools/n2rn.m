function n2rn(path)

%addpath('/var/www/htdocs/work/stralaceno2/tools/')

oldpath=cd(path);

z=dir('.');
for i=1:length(z)

    name = z(i).name;
    
    if isdir(name)
        if ( (name(1) ~= '.') & (~strcmp('CVS',upper(name))) )
            n2rn([path '/' name]);
        end
    else
        if strcmp(name((end-3):end),'.php')
            name2 = [name '.bak'];
            
            disp(name)

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
        end
    end

end

cd(oldpath);
addpath('/var/www/htdocs/work/ars/tools/')