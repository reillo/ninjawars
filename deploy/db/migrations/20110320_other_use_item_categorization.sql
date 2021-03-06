alter table item add column other_usable boolean default false;
update item set other_usable = true where item_internal_name in('shuriken', 'amanita', 'smokebomb', 'caltrops', 'dimmak', 'phosphor', 'ginsengroot', 'tigersalve', 'lantern', 'kunai', 'tessen');
insert into item_effects (_item_id, _effect_id) values ((select item_id from item where item_internal_name='kunai'), (select effect_id from effects where effect_name ='Wound')), ((select item_id from item where item_internal_name='kunai'), (select effect_id from effects where effect_name ='Pierce')), ((select item_id from item where item_internal_name='tessen'), (select effect_id from effects where effect_name ='Wound'));
update item set target_damage = 20 where item_internal_name = 'tessen';
update item set target_damage = 50 where item_internal_name = 'kunai';
