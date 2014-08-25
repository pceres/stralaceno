% logfile = '../diff.txt'; % generalo lanciando "git diff > diff.txt"


logfile = '../diff.txt'; % generalo lanciando "git diff > diff.txt"

z = dir(logfile);
if isempty(z)
    error('git diff logfile not found: %s',logfile)
end

fid = fopen(logfile, 'r');
testo = fread(fid, z(1).bytes, 'uint8=>char')';
fclose(fid);


z=regexp(testo,'diff \-\-git "?a([^\r\n]*?)"? "?b[^\r\n]*?"?\nold mode ([0-9]+)\nnew mode ([0-9]+)\n','tokens');
for i=1:length(z)
    cmd = sprintf('chmod %s "..%s"',z{i}{2}(4:end),z{i}{1});
    disp(cmd);
    system(cmd);
end
