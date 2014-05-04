-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 01, 2011 at 09:18 AM
-- Server version: 5.5.2
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `statsdump`
--

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

DROP TABLE IF EXISTS `weapons`;
CREATE TABLE IF NOT EXISTS `weapons` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(150) NOT NULL DEFAULT '0',
  `WEAPON` varchar(150) NOT NULL DEFAULT '0',
  `CLASS` varchar(150) NOT NULL DEFAULT '0',
  `IMAGE` varchar(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`ID`, `NAME`, `WEAPON`, `CLASS`, `IMAGE`) VALUES
(1, 'FireAxe', 'KW_Axe', 'Pyro', 'images/weaponicons/Killicon_fireaxe.png'),
(2, 'Bonesaw', 'KW_Bnsw', 'Medic', 'images/weaponicons/Killicon_bonesaw.png'),
(3, 'Bat', 'KW_Bt', 'Scout', 'images/weaponicons/Killicon_bat.png'),
(4, 'Bottle', 'KW_Bttl', 'Demoman', 'images/weaponicons/Killicon_bottle.png'),
(5, 'Minigun (Sasha)', 'KW_Cg', 'Heavy', 'images/weaponicons/Killicon_minigun.png'),
(6, 'Fist Punch', 'KW_Fsts', 'Heavy', 'images/weaponicons/Killicon_fists.png'),
(7, 'Flamethrower', 'KW_Ft', 'Pyro', 'images/weaponicons/Killicon_flamethrower.png'),
(8, 'Grenade Launcher', 'KW_Gl', 'Demoman', 'images/weaponicons/Killicon_grenade_launcher.png'),
(9, 'Knife', 'KW_Kn', 'Spy', 'images/weaponicons/Killicon_backstab.png'),
(10, 'Kukri', 'KW_Mctte', 'Sniper', 'images/weaponicons/Killicon_kukri.png'),
(11, 'Revolver', 'KW_Mgn', 'Spy', 'images/weaponicons/Killicon_revolver.png'),
(12, 'Syringe Gun', 'KW_Ndl', 'Medic', 'images/weaponicons/Killicon_syringegun.png'),
(13, 'Pistol', 'KW_Pistl', 'Engineer', 'images/weaponicons/Killicon_pistol.png'),
(14, 'Rocket Launcher', 'KW_Rkt', 'Soldier', 'images/weaponicons/Killicon_rocketlauncher.png'),
(15, 'Scattergun', 'KW_Sg', 'Scout', 'images/weaponicons/Killicon_scattergun.png'),
(16, 'Stickybomb Launcher', 'KW_Sky', 'Demoman', 'images/weaponicons/Killicon_stickybomb_launcher.png'),
(17, 'Sub-machine Gun', 'KW_Smg', 'Scout', 'images/weaponicons/Killicon_smg.png'),
(18, 'Sniper Rifle', 'KW_Spr', 'Sniper', 'images/weaponicons/Killicon_sniperrifle.png'),
(50, 'Shotgun', 'KW_Stgn', 'Pyro', 'images/weaponicons/Killicon_shotgun.png'),
(20, 'Wrench', 'KW_Wrnc', 'Engineer', 'images/weaponicons/Killicon_wrench.png'),
(21, 'Sentry', 'KW_Sntry', 'Engineer', 'images/weaponicons/Killicon_sentry1.png'),
(22, 'Shovel', 'KW_Shvl', 'Soldier', 'images/weaponicons/Killicon_shovel.png'),
(23, 'Pyro Hadouken Taunt', 'KW_taunt_pyro', 'Pyro', 'images/weaponicons/Killicon_hadouken.png'),
(24, 'The Axtinguisher', 'KW_Axtinguisher', 'Pyro', 'images/weaponicons/Killicon_axtinguisher.png'),
(25, 'The Flaregun', 'KW_Flaregun', 'Pyro', 'images/weaponicons/Killicon_flare_gun.png'),
(26, 'The Backburner', 'KW_backburner', 'Pyro', 'images/weaponicons/Killicon_backburner.png'),
(27, 'Minigun (Natascha)', 'KW_natascha', 'Heavy', 'images/weaponicons/Killicon_natascha.png'),
(28, 'The Blutsauger', 'KW_blutsauger', 'Medic', 'images/weaponicons/Killicon_blutsauger.png'),
(29, 'The KGB', 'KW_gloves', 'Heavy', 'images/weaponicons/Killicon_kgb.png'),
(30, 'Heavy Fist Taunt', 'KW_taunt_heavy', 'Heavy', 'images/weaponicons/Killicon_showdown.png'),
(31, 'The Ubersaw', 'KW_Ubersaw', 'Medic', 'images/weaponicons/Killicon_ubersaw.png'),
(32, 'The Huntsman', 'KW_tf_projectile_arrow', 'Sniper', 'images/weaponicons/Killicon_huntsmanhs.png'),
(33, 'The Ambassador', 'KW_ambassador', 'Spy', 'images/weaponicons/Killicon_ambassador.png'),
(34, 'The Sandman', 'KW_sandman', 'Scout', 'images/weaponicons/Killicon_sandman.png'),
(35, 'Huntsman Fire Arrow', 'KW_compound_bow', 'Sniper', 'images/weaponicons/Killicon_flaming_huntsman.png'),
(36, 'Scout BONK! Taunt', 'KW_taunt_scout', 'Scout', 'images/weaponicons/Killicon_home_run.png'),
(37, 'The Force-A-Nature', 'KW_force_a_nature', 'Scout', 'images/weaponicons/Killicon_force_a_nature.png'),
(38, 'Jarate', 'KW_jar', 'Sniper', 'images/weaponicons/Killicon_telefrag.png'),
(39, 'The Equalizer', 'KW_unique_pickaxe', 'Soldier', 'images/weaponicons/Killicon_equalizer.png'),
(40, 'The Direct Hit', 'KW_rocketlauncher_directhit', 'Soldier', 'images/weaponicons/Killicon_direct_hit.png'),
(41, 'Telefrag', 'KW_telefrag', 'Engineer', 'images/weaponicons/Killicon_telefrag.png'),
(42, 'Soldier Grenade Taunt', 'KW_taunt_soldier', 'Soldier', 'images/weaponicons/Killicon_kamikaze.png'),
(43, 'Targe Charge', 'KW_demoshield', 'Demoman', 'images/weaponicons/Killicon_chargin_targe.png'),
(44, 'The Eyelander', 'KW_sword', 'Demoman', 'images/weaponicons/Killicon_eyelander.png'),
(45, 'Demo Eyelander Taunt', 'KW_taunt_demoman', 'Demoman', 'images/weaponicons/Killicon_decapitation.png'),
(46, 'The Scottish Resistance', 'KW_sticky_resistance', 'Demoman', 'images/weaponicons/Killicon_scottish_resistance.png'),
(51, 'The Tribalman&#39;s Shiv', 'KW_tribalkukri', 'Sniper', 'images/weaponicons/Killicon_tribalman&#39;s_shiv.png'),
(52, 'The Scotsman&#39;s Skullcutter', 'KW_battleaxe', 'Demoman', 'images/weaponicons/Killicon_scotsman&#39;s_skullcutter.png'),
(53, 'Sandman Ball', 'KW_ball', 'Scout', 'images/weaponicons/Killicon_sandman_ball.png'),
(54, 'The Pain Train', 'KW_paintrain', 'Demoman', 'images/weaponicons/Killicon_pain_train.png'),
(56, 'The Homewrecker', 'KW_sledgehammer', 'Pyro', 'images/weaponicons/Killicon_homewrecker.png'),
(57, 'Pumpkin Bomb', 'KW_pumpkin', 'Scout', 'images/weaponicons/Killicon_pumpkin.png'),
(66, 'Goomba Stomp', 'KW_goomba', 'Scout', 'images/weaponicons/Killicon_home_run.png'),
(75, 'Level 1 Sentry', 'KW_SntryL1', 'Engineer', 'images/weaponicons/Killicon_sentry1.png'),
(76, 'Level 2 Sentry', 'KW_SntryL2', 'Engineer', 'images/weaponicons/Killicon_sentry2.png'),
(77, 'Level 3 Sentry', 'KW_SntryL3', 'Engineer', 'images/weaponicons/Killicon_sentry3.png'),
(78, 'The Frontier Justice', 'KW_frontier_justice', 'Engineer', 'images/weaponicons/Killicon_frontier_justice.png'),
(79, 'The Wrangler', 'KW_wrangler_kill', 'Engineer', 'images/weaponicons/Killicon_wrangler.png'),
(80, 'The Gunslinger', 'KW_robot_arm', 'Engineer', 'images/weaponicons/Killicon_gunslinger.png'),
(81, 'The Lugermorph', 'KW_maxgun', 'Scout', 'images/weaponicons/Killicon_maxgun.png'),
(83, 'The Southern Hospitality', 'KW_southern_hospitality', 'Engineer', 'images/weaponicons/Killicon_southern_hospitality.png'),
(84, 'Bleed Kill', 'KW_bleed_kill', 'Sniper', 'images/weaponicons/Killicon_bleed.png'),
(86, 'Gunslinger Taunt Kill', 'KW_robot_arm_blender_kill', 'Engineer', 'images/weaponicons/Killicon_gunslinger_triple_punch.png'),
(87, 'Frontier Justice Taunt Kill', 'KW_taunt_guitar_kill', 'Engineer', 'images/weaponicons/Killicon_dischord.png'),
(88, 'The Big Kill', 'KW_samrevolver', 'Spy', 'images/weaponicons/Killicon_samgun.png'),
(89, 'The Shortstop', 'KW_short_stop', 'Scout', 'images/weaponicons/Killicon_shortstop.png'),
(90, 'Holy Mackerel', 'KW_holy_mackerel', 'Scout', 'images/weaponicons/Killicon_holy_mackerel.png'),
(91, 'Powerjack', 'KW_powerjack', 'Pyro', 'images/weaponicons/Killicon_powerjack.png'),
(92, 'Degreaser', 'KW_degreaser', 'Pyro', 'images/weaponicons/Killicon_degreaser.png'),
(93, 'Vita-Saw', 'KW_battleneedle', 'Medic', 'images/weaponicons/Killicon_vita-saw.png'),
(94, 'Your Eternal Reward', 'KW_eternal_reward', 'Spy', 'images/weaponicons/Killicon_your_eternal_reward.png'),
(95, 'L&#39;Etranger', 'KW_letranger', 'Spy', 'images/weaponicons/Killicon_l&#39;etranger.png'),
(96, 'Bushwacka', 'KW_bushwacka', 'Sniper', 'images/weaponicons/Killicon_bushwacka.png'),
(97, 'Gloves of Running Urgently', 'KW_urgentgloves', 'Heavy', 'images/weaponicons/Killicon_gru.png'),
(98, 'Sydney Sleeper', 'KW_sleeperrifle', 'Sniper', 'images/weaponicons/Killicon_sydney_sleeper.png'),
(99, 'Black Box', 'KW_blackbox', 'Spy', 'images/weaponicons/Killicon_black_box.png'),
(100, 'Gunslinger Combo Kill', 'KW_robot_arm_combo_kill', 'Engineer', 'images/weaponicons/Killicon_gunslinger_triple_punch.png'),
(101, 'Frying Pan', 'KW_fryingpan', 'All', 'images/weaponicons/Killicon_frying_pan.png'),
(102, 'Horseless Headless Horsemann&#39;s Headtaker', 'KW_headtaker', 'Demoman', 'images/weaponicons/Killicon_horseless_headless_horsemann&#39;s_headtaker.png'),
(103, 'Iron Curtain', 'KW_iron_curtain', 'Heavy', 'images/weaponicons/Killicon_iron_curtain.png'),
(104, 'Candy Cane', 'KW_candy_cane', 'Scout', 'images/weaponicons/Killicon_candy_cane.png'),
(105, 'Boston Basher', 'KW_boston_basher', 'Scout', 'images/weaponicons/Killicon_boston_basher.png'),
(106, 'Back Scratcher', 'KW_back_scratcher', 'Pyro', 'images/weaponicons/Killicon_back_scratcher.png'),
(107, 'Ullapool Caber', 'KW_ullapool_caber', 'Demoman', 'images/weaponicons/Killicon_ullapool_caber.png'),
(108, 'Loch-n-Load', 'KW_lochnload', 'Demoman', 'images/weaponicons/Killicon_loch-n-load.png'),
(109, 'Claidheamh Mor', 'KW_claidheamohmor', 'Demoman', 'images/weaponicons/Killicon_claidheamh_mor.png'),
(110, 'Brass Beast', 'KW_brassbeast', 'Heavy', 'images/weaponicons/Killicon_brass_beast.png'),
(111, 'Warrior&#39;s Spirit', 'KW_bearclaws', 'Heavy', 'images/weaponicons/Killicon_warrior&#39;s_spirit.png'),
(112, 'Fists of Steel', 'KW_steelfists', 'Heavy', 'images/weaponicons/Killicon_fists_of_steel.png'),
(113, 'Jag', 'KW_wrench_jag', 'Engineer', 'images/weaponicons/Killicon_jag.png'),
(114, 'The Amputator', 'KW_amputator', 'Medic', 'images/weaponicons/Killicon_amputator.png'),
(115, 'Crusader&#39;s Crossbow', 'KW_healingcrossbow', 'Medic', 'images/weaponicons/Killicon_crusader&#39;s_crossbow.png'),
(116, 'Combat Mini-Sentry Gun', 'KW_minisentry', 'Engineer', 'images/weaponicons/Killicon_minisentry.png'),
(117, 'Ullapool Caber Explosion', 'KW_ullapool_caber_explosion', 'Demoman', 'images/weaponicons/Killicon_ullapool_caber_explode.png'),
(118, 'Worms Grenade', 'KW_worms_grenade', 'Soldier', 'images/weaponicons/Killicon_hhg.png'),
(119, 'Sharpened Volcano Fragment', 'KW_lava_axe', 'Pyro', 'images/weaponicons/Killicon_sharpened_volcano_fragment.png'),
(120, 'Sun-on-a-Stick', 'KW_sun_bat', 'Scout', 'images/weaponicons/Killicon_sun-on-a-stick.png'),
(121, 'Fan O&#39;War', 'KW_warfan', 'Scout', 'images/weaponicons/Killicon_fan_owar.png'),
(122, 'Conniver&#39;s Kunai', 'KW_kunai', 'Spy', 'images/weaponicons/Killicon_connivers_kunai.png'),
(123, 'Half-Zatoichi', 'KW_katana', 'Soldier', 'images/weaponicons/Killicon_half-zatoichi.png'),
(124, 'Three-Rune Blade', 'KW_witcher_sword', 'Scout', 'images/weaponicons/Killicon_three-rune_blade.png'),
(125, 'Maul', 'KW_maul', 'Pyro', 'images/weaponicons/Killicon_maul.png'),
(126, 'Soda Popper', 'KW_soda_popper', 'Scout', 'images/weaponicons/Killicon_soda_popper.png'),
(127, 'Ailier', 'KW_the_winger', 'Scout', 'images/weaponicons/Killicon_winger.png'),
(128, 'Atomizer', 'KW_atomizer', 'Scout', 'images/weaponicons/Killicon_atomizer.png'),
(129, 'Liberty Launcher', 'KW_liberty_launcher', 'Soldier', 'images/weaponicons/Killicon_liberty_launcher.png'),
(130, 'Reserve Shooter', 'KW_reserve_shooter', 'Soldier', 'images/weaponicons/Killicon_reserve_shooter.png'),
(131, 'Disciplinary Action', 'KW_disciplinary_action', 'Soldier', 'images/weaponicons/Killicon_disciplinary_action.png'),
(132, 'Market Gardener', 'KW_market_gardener', 'Soldier', 'images/weaponicons/Killicon_market_gardener.png'),
(133, 'Mantreads', 'KW_mantreads', 'Soldier', 'images/weaponicons/Killicon_mantreads.png'),
(134, 'Detonator', 'KW_detonator', 'Pyro', 'images/weaponicons/Killicon_detonator.png'),
(135, 'Persian Persuader', 'KW_persian_persuader', 'Demoman', 'images/weaponicons/Killicon_persian_persuader.png'),
(136, 'Splendid Screen', 'KW_splendid_screen', 'Demoman', 'images/weaponicons/Killicon_splendid_screen.png'),
(137, 'Tomislav', 'KW_tomislav', 'Heavy', 'images/weaponicons/Killicon_tomislav.png'),
(138, 'Family Business', 'KW_family_business', 'Heavy', 'images/weaponicons/Killicon_family_business.png'),
(139, 'Eviction Notice', 'KW_eviction_notice', 'Heavy', 'images/weaponicons/Killicon_eviction_notice.png'),
(140, 'Overdose', 'KW_proto_syringe', 'Medic', 'images/weaponicons/Killicon_overdose.png'),
(141, 'Solemn Vow', 'KW_solemn_vow', 'Medic', 'images/weaponicons/Killicon_solemn_vow.png'),
(142, 'Bazaar Bargain', 'KW_bazaar_bargain', 'Sniper', 'images/weaponicons/Killicon_bazaar_bargain.png'),
(143, 'Shahanshah', 'KW_shahanshah', 'Sniper', 'images/weaponicons/Killicon_shahanshah.png'),
(144, 'Enforcer', 'KW_enforcer', 'Spy', 'images/weaponicons/Killicon_enforcer.png'), 	
(145, 'Big Earner', 'KW_big_earner', 'Spy', 'images/weaponicons/Killicon_big_earner.png'),
(146, 'Postal Pummeler', 'KW_mailbox', 'Pyro', 'images/weaponicons/Killicon_postal_pummeler.png'), 	
(147, 'Nessie&#39;s Nine Iron', 'KW_golfclub', 'Demoman', 'images/weaponicons/Killicon_nessie&#39;s_nine_iron.png'),
(148, 'Cow Mangler 5000', 'KW_mangler', 'Soldier', 'images/weaponicons/Killicon_cow_mangler_5000.png'),
(149, 'Righteous Bison', 'KW_bison', 'Soldier', 'images/weaponicons/Killicon_righteous_bison.png'),
(150, 'Original', 'KW_QuakeRL', 'Soldier', 'images/weaponicons/Killicon_original.png'),
(151, 'Cow Mangler 5000', 'KW_ManglerReflect', 'Soldier', 'images/weaponicons/Killicon_fire.png'),
(152, 'Widowmaker', 'KW_Widowmaker', 'Engineer', 'images/weaponicons/Killicon_widowmaker.png'),
(153, 'Short Circuit', 'KW_Short_Circuit', 'Engineer', 'images/weaponicons/Killicon_short_circuit.png'),
(154, 'Machina', 'KW_Machina', 'Sniper', 'images/weaponicons/Killicon_machina.png'),
(155, 'Machina', 'KW_Machina_DoubleKill', 'Sniper', 'images/weaponicons/Killicon_machina_penetrate.png'),
(156, 'Diamondback', 'KW_Diamondback', 'Spy', 'images/weaponicons/Killicon_diamondback.png'),
(171, 'Unarmed Combat', 'KW_UnarmedCombat', 'Scout', 'images/weaponicons/Killicon_unarmed_combat.png'),
(157, 'Wanga Prick', 'KW_WangaPrick', 'Spy', 'images/weaponicons/Killicon_wanga_prick.png'),
(158, 'Scottish Handshake', 'KW_ScottishHandshake', 'Demoman', 'images/weaponicons/Killicon_scottish_handshake.png'),
(159, 'Conscientious Objector', 'KW_ConscientiousObjector', 'All', 'images/weaponicons/Killicon_conscientious_objector.png'),
(160, 'Saxxy', 'KW_Saxxy', 'All', 'images/weaponicons/Killicon_saxxy.png'),
(161, 'MONOCULUS Stuns', 'EyeBossStuns', 'All', 'images/weaponicons/Killicon_monoculus.png'),
(162, 'MONOCULUS Kills', 'EyeBossKills', 'All', 'images/weaponicons/Killicon_monoculus.png'),
(163, 'Phlogistinator', 'KW_phlogistinator', 'Pyro', 'images/weaponicons/Killicon_phlogistinator.png'),
(164, 'Manmelter', 'KW_manmelter', 'Pyro', 'images/weaponicons/Killicon_manmelter.png'),
(165, 'Third Degree', 'KW_thirddegree', 'Pyro', 'images/weaponicons/Killicon_third_degree.png'),
(166, 'Holiday Punch', 'KW_holiday_punch', 'Heavy', 'images/weaponicons/Killicon_holiday_punch.png'),
(167, 'Pomson 6000', 'KW_pomson', 'Engineer', 'images/weaponicons/Killicon_pomson_6000.png'),
(168, 'Eureka Effect', 'KW_eureka_effect', 'Engineer', 'images/weaponicons/Killicon_eureka_effect.png'),
(169, 'Sharp Dresser', 'KW_sharp_dresser', 'Spy', 'images/weaponicons/Killicon_sharp_dresser.png'),
(170, 'Spy-cicle', 'KW_spy_cicle', 'Spy', 'images/weaponicons/Killicon_spy-cicle.png');