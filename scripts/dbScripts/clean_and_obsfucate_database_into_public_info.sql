update players set uname = (substring(uname, 1, 1) || player_id) where !(uname in('test', 'tchalvak'));
update clan set clan_name = (substring(clan_name, 1, 1) || clan_id);
update players set pname = 'password';
update players set email = player_id || 'example@example.com';
update players set gold=(player_id/2), kills=(player_id/100), messages='', ip='127.0.0.33';
update past_stats set stat_result='no-one' where stat_type='Yesterday''s Vicious Killer';
truncate chat;
truncate events;
truncate messages;
truncate settings;
truncate ppl_online;
truncate dueling_log;
truncate levelling_log;
truncate players_backup;
truncate players_flagged;
grant all on chat, players, events, messages, dueling_log, levelling_log, flags, past_stats, clan, clan_player, player_rank, players_backup, players_flagged, ppl_online, rankings, settings, time to developers;
