/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : tourbuy

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-09-04 16:27:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pm_access`
-- ----------------------------
DROP TABLE IF EXISTS `pm_access`;
CREATE TABLE `pm_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupId` (`role_id`) USING BTREE,
  KEY `nodeId` (`node_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_access
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_ad`
-- ----------------------------
DROP TABLE IF EXISTS `pm_ad`;
CREATE TABLE `pm_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL COMMENT '排序',
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_ad
-- ----------------------------
INSERT INTO `pm_ad` VALUES ('1', '1', '0-1-', '贝拉米广告', '/uploads/images/20190729/29a16f509df847402f50d82de3fa4f62.jpg', '111', '50', '1563768353', '1564393940');
INSERT INTO `pm_ad` VALUES ('2', '1', '0-1-', 'a2广告', '/uploads/images/20190729/f78473ebaeb30ade50f61c7f2a6f1e4e.png', '111', '50', '1563768399', '1564393620');
INSERT INTO `pm_ad` VALUES ('3', '1', '0-1-', 'swisse广告', '/uploads/images/20190729/629546cbfee3e4e6c68d1611aed44af7.png', '1', '50', '1563788399', '1564393579');
INSERT INTO `pm_ad` VALUES ('4', '2', '0-2-', '测试', '/uploads/images/20190816/0b929b1116175b887c7c57a85e7a58bd.jpg', '1', '50', '1565969493', '1565969493');

-- ----------------------------
-- Table structure for `pm_address`
-- ----------------------------
DROP TABLE IF EXISTS `pm_address`;
CREATE TABLE `pm_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `county` varchar(30) NOT NULL,
  `addressDetail` varchar(100) NOT NULL,
  `front` varchar(255) NOT NULL,
  `back` varchar(255) NOT NULL,
  `sn` varchar(50) NOT NULL,
  `def` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_address
-- ----------------------------
INSERT INTO `pm_address` VALUES ('1', '10001', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', '', '', '', '0');
INSERT INTO `pm_address` VALUES ('2', '10002', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '2222222', '1');
INSERT INTO `pm_address` VALUES ('3', '10002', '张明', '18500000000', '辽宁省', '大连市', '旅顺口区', '石鼓路331号西', '', '', '', '0');

-- ----------------------------
-- Table structure for `pm_article`
-- ----------------------------
DROP TABLE IF EXISTS `pm_article`;
CREATE TABLE `pm_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `title` varchar(300) NOT NULL,
  `short` varchar(200) NOT NULL,
  `from` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `author` varchar(50) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `picname` varchar(500) NOT NULL,
  `keyword` varchar(300) NOT NULL,
  `comm` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `flash` int(11) NOT NULL,
  `bold` int(11) NOT NULL,
  `red` int(11) NOT NULL,
  `intr` varchar(500) NOT NULL,
  `content` longtext NOT NULL,
  `hit` int(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL,
  `editer` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0草稿1正常',
  `del` tinyint(4) NOT NULL COMMENT '0正常1删除',
  `updateTime` int(10) NOT NULL,
  `createTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_article
-- ----------------------------
INSERT INTO `pm_article` VALUES ('106', '1', '0-1-', '患有几种病，医生会劝你戒酒，爱喝的人做好心理准备', '', '', '', '', '', '/uploads/images/20190513/03a72648e168b24366d6bf53e8f6e5e7_180_120.jpg,/uploads/images/20190513/52212ce437a427653bca16b6be557ea3_180_120.jpg,/uploads/images/20190513/3bbbc4f446277e91c4e76c8fcd0af4d9_180_120.jpg', '', '0', '0', '0', '0', '0', '自古以来中国的&ldquo;酒文化&rdquo;就根深蒂固，很多人为了工作和应酬，不得不经常喝酒，但小九要提醒，有些人是不可以喝酒的，有些人因为患病不能喝酒，有些人因为其他原因不能喝酒，那么，究竟哪些人在喝酒&ldquo;黑名单&rdquo;呢？有几类人建议滴酒不沾：●肝病患者酒精最先要经过肝脏的分…', '<p>自古以来中国的&ldquo;酒文化&rdquo;就根深蒂固，很多人为了工作和应酬，不得不经常喝酒，但小九要提醒，有些人是不可以喝酒的，有些人因为患病不能喝酒，有些人因为其他原因不能喝酒，那么，究竟哪些人在喝酒&ldquo;黑名单&rdquo;呢？</p>\n<p>有几类人建议滴酒不沾：</p>\n<p><img src=\"/uploads/images/20190513/03a72648e168b24366d6bf53e8f6e5e7.jpg\" alt=\"\" /></p>\n<p>●肝病患者</p>\n<p>酒精最先要经过肝脏的分解才能彻底代谢，直接对肝脏细胞产生毒害作用，所以喝酒是最伤肝的。肝病患者的肝脏功能下降，降低了转化酒精的能力，患有肝病的人如果长期喝酒就等于慢性自杀，会造成酒精肝、肝硬化、脂肪肝等。</p>\n<p>●胃病患者</p>\n<p>啤酒中含有大量的二氧化碳，患有胃病的人喝了会损伤胃粘膜，导致胃酸增加，引起腹胀、腹痛等症状。尤其是酗酒，容易引起胃穿孔导致大量出血而危及生命。所以患有胃溃疡、胃炎、肠炎的人都不能喝酒，以免加重病情。</p>\n<p><img src=\"/uploads/images/20190513/52212ce437a427653bca16b6be557ea3.jpg\" alt=\"\" /></p>\n<p>●高血压和心脏病患者</p>\n<p>酒精，一是使大脑兴奋，情绪激动；二是使血管扩张，血压升高，这样易发生破血管而导致死亡。心脏病患者喝酒容易发生心律不齐，心跳加速等不良症状。</p>\n<p>●尿结石患者</p>\n<p>&nbsp;</p>\n<p>制造啤酒的原料主要是大麦芽，大麦芽内含有草酸、钙和鸟核苷酸，这几种物质都是极易导致结石的。所以本身患有尿道结石的患者不能喝酒，会加重病情。</p>\n<p>●糖尿病患者</p>\n<p>糖尿病患者在服用磺脲类降糖药或注射胰岛素时不能喝酒，因为酒精会加重药物引起的低血糖反应，出现头晕，严重的出现脸色苍白或潮红、冒冷汗、心慌、恶心、走路不稳等。因此，糖尿病人在服用降糖药或注射胰岛素时，不能饮酒。</p>\n<p>●近视眼、青光眼患者</p>\n<p>甲醇是几乎所有酒类中含有的物质，同时也是人体视网膜的杀手，它对视网膜有明显的毒副作用，使视网膜产生视色素的能力减弱，导致眼睛难以适应光线变化。因此，近视眼、青光眼病人最好不要饮酒。</p>\n<p>●妊娠期、哺乳期妇女和儿童</p>\n<p><img src=\"/uploads/images/20190513/3bbbc4f446277e91c4e76c8fcd0af4d9.jpg\" alt=\"\" /></p>\n<p>酒精会通过脐带输送到胎儿体内，严重影响胎儿的发育，容易导致胎儿发育不良甚至流产，所以妊娠期妇女不能喝酒；此外，哺乳期的妇女如果喝了啤酒容易减少乳汁分泌，因为啤酒中含有的大麦芽成分容易回奶；未成年儿童由于大脑发育还未健全，喝酒容易损伤大脑，为了儿童的身心健康，不应让儿童喝酒。</p>', '99', '50', 'admin', '2019', '1', '0', '1557711265', '1557676800');
INSERT INTO `pm_article` VALUES ('105', '1', '0-1-', '不打算戒烟了？吸烟的四个坏处，还是想说给你听', '', '', '', '', '', '/uploads/images/20190513/8aaecaee0265ab1911db3fe3f119ff24_180_120.jpg,/uploads/images/20190513/2099d251c6fe8410c6014819a1a32f68_180_120.jpg,/uploads/images/20190513/38c6c5373a9a609ca0d2dfbdeea24d9c_180_120.jpg', '', '0', '0', '0', '0', '0', '大家都知道吸烟有害健康，但是对健康的伤害到底在哪里，多数人说不上来。也正是因为这种模糊的概念导致人们只有戒烟的想法，却没有戒烟的决心，因为并不清楚抽烟对身体会造成哪些具体伤害，本文就来介绍一下吸烟的坏处，同时来介绍一下如何快速戒烟。吸烟的危害，你总是不愿面对！◇伤肺吸烟对肺部的伤害这一点相信多数人都…', '<p>大家都知道吸烟有害健康，但是对健康的伤害到底在哪里，多数人说不上来。也正是因为这种模糊的概念导致人们只有戒烟的想法，却没有戒烟的决心，因为并不清楚抽烟对身体会造成哪些具体伤害，本文就来介绍一下吸烟的坏处，同时来介绍一下如何快速戒烟。</p>\n<p>吸烟的危害，你总是不愿面对！</p>\n<p><img src=\"/uploads/images/20190513/8aaecaee0265ab1911db3fe3f119ff24.jpg\" alt=\"\" /></p>\n<p>◇伤肺</p>\n<p>吸烟对肺部的伤害这一点相信多数人都知道，在抽烟的过程当中烟草当中的有害物质会进入到肺部，而毒素在肺部停留不能及时从身体排出去之后就会慢慢侵蚀肺部健康。</p>\n<p>◇口臭</p>\n<p>一个人是否抽烟，只需要开口说几句话就能判断，因为多数抽烟人都存在嘴巴味道难闻的症状，长期以往味道会越来越难消除，因此在跟别人说话的时候很容易传递到对方鼻子中，而且抽烟的人，烟雾当中的焦油等也会停留附注在牙齿上，出现牙齿变黄，同时也会加重口腔异味的症状。</p>\n<p><img src=\"/uploads/images/20190513/2099d251c6fe8410c6014819a1a32f68.jpg\" alt=\"\" /></p>\n<p>◇骨密度低</p>\n<p>有相关研究发现，抽烟人群的骨密度要明显低于不抽烟人群的骨密度，在中老年人身上这种情况会更加严重，同样摔一跤不抽烟的人可能没事儿，抽烟人不仅容易骨折，骨折恢复的时间也较正常人更长，可见抽烟会让人体骨骼变脆。</p>\n<p>◇脱发</p>\n<p>吸烟会导致脱发这个相信多数人都不太清楚，这个根据多年临床观察研究发现的，那些每天抽烟超过一包的人脱发症状非常明显，这是因为在香烟当中含有很多有毒物质，在进入人体之后会伤害到头皮毛囊，会导致脱发。</p>\n<p><img src=\"/uploads/images/20190513/38c6c5373a9a609ca0d2dfbdeea24d9c.jpg\" alt=\"\" /></p>\n<p><strong>戒烟最管用的方法</strong></p>\n<p><strong>加强戒烟意识</strong></p>\n<p>想要戒烟一定要有戒烟的决心，想要增加戒烟的决心不妨上网多搜索一下吸烟人群的肺部图片，听听别人的案例，你会突然觉得吸烟让人毛骨悚然，自然在戒烟方面也会更加上心。</p>\n<p><strong>寻找替代方法</strong></p>\n<p>对于烟民来说，想要戒烟确实很难，尤其是当烟瘾发作之后让自己很是苦恼，因此可以适当寻找一些烟瘾出现之后的替代方法，比如增加手部活动，备点小零食或者玩个小游戏，这些都可以帮助转移注意力。</p>\n<p><img src=\"/uploads/images/20190513/154d7f7a2fdaafdb0165ff50d57ef381.jpg\" alt=\"\" /></p>\n<p><strong>&nbsp;远离烟源</strong></p>\n<p>想要彻底戒烟，确实需要很大的毅力，在不确定自己毅力是否可以达到的前提下，可以远离诱发自己吸烟的因素，首先需要远离烟，其次需要远离烟灰缸这类让你回忆起烟的东西，对于自己以前的烟友可以在戒烟初期适当远离。</p>', '68', '50', 'admin', '2019', '1', '0', '1557711092', '1557676800');
INSERT INTO `pm_article` VALUES ('104', '2', '0-2-', '人为什么会胖？带你了解脂肪的“前生今世”，不再闻脂色变', '', '', '', '', '', '/uploads/images/20190505/b796153f3c8ef5cbe1ecaef1518a4566_180_120.jpg', '', '0', '0', '0', '0', '0', '一直在说减肥减脂，一直在尝试各种方法，然而你是否知道，人体的脂肪究竟是什么东西，又是怎么产生的？不如先停下减肥的脚步，了解一下这个疑问，或许对减肥更有帮助。讨厌的脂肪到底是怎么来的？很多人以为脂肪是直接吃进体内的。实际上，人身上的脂肪都是在人体内合成的。一般有两个来源：食物中的脂肪进入消化道后被水解…', '<p>一直在说减肥减脂，一直在尝试各种方法，然而你是否知道，人体的脂肪究竟是什么东西，又是怎么产生的？不如先停下减肥的脚步，了解一下这个疑问，或许对减肥更有帮助。</p>\n<p><img src=\"/uploads/images/20190505/b796153f3c8ef5cbe1ecaef1518a4566.jpg\" alt=\"\" /></p>\n<p><strong>讨厌的脂肪到底是怎么来的？</strong></p>\n<p>很多人以为脂肪是直接吃进体内的。实际上，人身上的脂肪都是在人体内合成的。一般有两个来源：</p>\n<p>食物中的脂肪进入消化道后被水解成脂肪酸，然后被小肠吸收，进入人体内功能。当细胞从中摄取足够的热量之后，剩下的脂肪酸就会重新被合成甘油三酯，储存在体内的脂肪细胞中，于是人体的皮下就会出现一层脂肪。</p>\n<p>食物中供能的物质还有糖类和蛋白质，它们也同样进入人体内参与组织器官的运作，如果消耗结束还有剩余，就会被胰岛素转化成脂肪酸，最后还是被合成脂肪储存起来。</p>\n<p><strong>怎样才能消耗掉这些可恶的脂肪？</strong></p>\n<p>消耗脂肪的难点就在于，人体在取用热量的次序上，脂肪并不是排在最前面的。一般来说，当身体需要使用能量时，会先使用碳水化合物，碳水化合物不够用时，才会取用体内的脂肪，最后才会动用蛋白质。</p>\n<p>显然，想更快速消耗脂肪，就要让身体消耗热量的效率加快，最基础的办法，就是提高基础代谢率。有这几个方法可以使用：</p>\n<p>1、 坚持吃早餐。因为早晨人体的代谢水平最强，早餐可以激活人体的代谢活动，不吃早餐等于一整天代谢活动都将变得低迷；</p>\n<p>2、 多吃蛋白质。人体消化蛋白质时需要动用更多热量，有助于提高消耗脂肪的效率；</p>\n<p>3、 注意补铁。如果血液中铁含量不够，运送给细胞和器官的氧气也会不够，就会影响代谢活动进行；</p>\n<p>4、 多喝水。水分也是人体代谢活动的重要参与物质，所以想加速消耗脂肪，喝水不可少。</p>\n<p>还有，脂肪也并不是那么顽固，要调动脂肪参与供能，有氧运动也是一把好手。</p>\n<p>有氧运动可以让脂肪直接参与供能，尤其在时间够长、强度中等的运动中，脂肪消耗的速度能达到平常的10倍，甚至脂肪合成的速度也会被减缓。进行有氧运动方式也很多，慢跑、骑车、游泳、跳绳都是简单且高效的方法，建议每周安排4~5天参加有氧运动，并且每次运动最好维持30分钟以上。</p>\n<p>了解了脂肪的来龙去脉，相信你也能更加明白减肥应该如何着手。其实脂肪一点都不可怕，只要用科学方法对待，就一定可以打败它。</p>', '100', '50', 'admin', '2019', '1', '0', '1557036805', '1556985600');
INSERT INTO `pm_article` VALUES ('107', '9', '0-9-', '劝告：让你“一夜变老”的几个习惯，是时候改正了', '', '', '', '', '', '/uploads/images/20190513/4427e70d03c0d7510201ef7dec442ccc_180_120.jpg', '', '0', '0', '0', '0', '0', '所有人都希望永葆年轻，但衰老不可避免，但我们可以通过我们日常生活中的良好习惯，来推迟衰老的到来，来看看哪些日常习惯会加速衰老，以及我们应该怎样怎么做，才能对抗衰老。这些习惯加快衰老过程，长点心！1.喝酒吸烟酗酒能使人体内自由基增多，自由基是人类衰老的根源。活性氧自由基在体内具有一定生理功能，但过多就…', '<p>所有人都希望永葆年轻，但衰老不可避免，但我们可以通过我们日常生活中的良好习惯，来推迟衰老的到来，来看看哪些日常习惯会加速衰老，以及我们应该怎样怎么做，才能对抗衰老。</p>\n<p><img src=\"/uploads/images/20190513/4427e70d03c0d7510201ef7dec442ccc.jpg\" alt=\"\" /></p>\n<p>这些习惯加快衰老过程，长点心！</p>\n<p>1.喝酒</p>\n<p>吸烟酗酒能使人体内自由基增多，自由基是人类衰老的根源。活性氧自由基在体内具有一定生理功能，但过多就会对人体健康具有破坏作用，能够加速人体老化，尤其是皮肤老化最为严重，会出现斑点及皱纹。</p>\n<p>2.不良的饮食习惯</p>\n<p>（1）我们日常生活有许多人爱吃油炸、煎烤的食物，殊不知这些食物中含有大量的致癌物质丙烯酰胺，这些物质积蓄于人体，增加人体内氧化应激反应，产生大量自由基，破坏人体健康的同时，也会加速人体细胞的衰老；</p>\n<p>（2）油炸食品中含有大量的能量和脂肪，会造成人体肥胖、内分泌失调，损害组织功能，加速机体衰老；爱吃腌制食品，腌制的食品中含有硝酸盐，在肠道细菌作用下可还原为有毒的亚硝酸盐，亚硝酸盐会降低细胞活力，长期食用会引发人体早衰；</p>\n<p>（3）摄入过多含铝食品，比如不正规厂家生产的油条、膨化食物、加工水产等。铝在体内能与多种蛋白结合，影响体内多种生化反应，影响大脑功能，导致记忆减退，加速衰老；</p>\n<p>（4）摄入过多糖类。过多的糖会引起体内&ldquo;糖基化&rdquo;的过程，会让皮肤失弹性失光泽、毛孔粗大皱纹增多等引发衰老问题。</p>\n<p>3.长期接触紫外线</p>\n<p>人体皮肤在紫外线的作用下，为了保护皮肤收到紫外线的损伤，会分泌一种黑色素的物质，黑色素合成增加会导致色素沉着，出现黑斑、雀斑等形象美观的斑点，同时还破坏皮肤会的保湿能力，皮肤开始干燥脱水，形成皱纹。皮肤表层细胞死亡产生过多角质，使皮肤开始变得肥厚，松弛。</p>\n<p>4.面部表情过于丰富</p>\n<p>习惯性皱眉容易产生眉宇间的皱纹、经常大笑会导致法令纹加深、同时会在眼角产生鱼尾纹，爱抬眼眶容易产生抬头纹，经常撅嘴撇嘴容易产生法令纹和唇部皱纹。</p>\n<p>5.肌肤补水不够</p>\n<p>皮肤表层是角质层，是我们皮肤细胞死亡后所堆砌起来的细胞结构，能够帮助皮肤抵挡外界的各种物理以及化学伤害，同时还能帮助皮肤吸收空气中的水分滋养皮肤，但外界环境过于干燥时也会从体外吸收水分。若外界环境湿度较低时，皮肤没有充分给予补水，那么我们的皮肤将会处在一个失水的状态，长期以往皮肤就会变得粗糙、松弛、甚至出现皱纹；</p>\n<p>除了肌肤的直接补水外，我们也需要从内部摄入水，也就是要多喝水，水份摄取不够，油脂分泌就会减少，皮肤就更加容易脱水。</p>\n<p>6.熬夜，睡眠不足</p>\n<p>熬夜会导致黑眼圈的出现，皮肤也会变差、出现干燥、弹性差、水肿、暗疮等问题。而且熬夜还会使内分泌失调，促进衰老。</p>\n<p><img src=\"/uploads/images/20190513/291090edec23cf8f558ddc2c59d077c4.jpg\" alt=\"\" /></p>\n<p>有什么办法让衰老来得慢一些？</p>\n<p>★养成规律作息的好习惯、不熬夜、保持充足睡眠。</p>\n<p>★饮食习惯好，不吃油腻食物不吃油炸，多喝水，多吃水果蔬菜等富含维生素的食物，维生素C能有效帮助抗衰老。</p>\n<p>★养成每天排便，不忍便的习惯。</p>\n<p>★做好防晒和皮肤补水。就算没有太阳，环境中也会有紫外线，同样需要做好防晒。</p>\n<p>★戒烟控酒，也要避免二手烟中的有害物质吸入，喝酒要适量。</p>', '85', '50', 'admin', '2019', '1', '0', '1557711434', '1557676800');
INSERT INTO `pm_article` VALUES ('102', '1', '0-1-', '为何很多中年男人都有“啤酒肚”，背后无非是这几个原因', '', '', '', '', '', '/uploads/images/20190505/eef5fdc85b13eb083a53f5f325d79279_180_120.jpg,/uploads/images/20190505/fd89ab87e73f8aedc9a19894cabbd71b_180_120.jpg,/uploads/images/20190505/0849832d0a591e2b19d936b61051becf_180_120.jpg', '', '0', '0', '0', '0', '0', '炎热的夏天，酷暑难耐，喝上一杯冰凉的啤酒，会让人浑身凉爽。不过一些人认为，经常喝啤酒会形成啤酒肚，对身体健康不利。难道喝啤酒会长肚子，啤酒肚真的是由于喝啤酒形成的吗，男人啤酒肚的原因到底是什么，一旦有了啤酒肚该如何减掉呢？啤酒肚的原因在人们的传统意识中，啤酒肚是因为经常喝啤酒所导致，不过经过科学研究…', '<p>炎热的夏天，酷暑难耐，喝上一杯冰凉的啤酒，会让人浑身凉爽。</p>\n<p>不过一些人认为，经常喝啤酒会形成啤酒肚，对身体健康不利。</p>\n<p>难道喝啤酒会长肚子，啤酒肚真的是由于喝啤酒形成的吗，男人啤酒肚的原因到底是什么，一旦有了啤酒肚该如何减掉呢？</p>\n<p><img src=\"/uploads/images/20190505/eef5fdc85b13eb083a53f5f325d79279.jpg\" alt=\"\" /></p>\n<p><strong>啤酒肚的原因</strong></p>\n<p>在人们的传统意识中，啤酒肚是因为经常喝啤酒所导致，不过经过科学研究发现，啤酒的热量并不高，喝啤酒的多少对腹部的影响并不大，经常喝啤酒的人与不喝啤酒的人相比，腹部也不会变的更粗，体重也不会变的更重。</p>\n<p>因此，喝啤酒与啤酒肚的关系并不大，啤酒肚的形成主要原因并不是喝啤酒，而以下原因则是形成啤酒肚的主要方面。</p>\n<p><strong>1.营养过剩</strong></p>\n<p>一些人吃东西没有节制，尤其是那些暴饮暴食的人，喜欢吃高热量、高脂肪食物的人，如果缺少运动，则会导致摄入营养过多，营养过剩形成脂肪堆积在腹部，自然而然就形成了所谓的啤酒肚。</p>\n<p><strong>2.生活没有规律</strong></p>\n<p>生活没有规律，工作压力过大，会导致神经系统错乱，腰腹周围的血液循环会受到影响，导致脂肪在腰腹周围堆积，形成啤酒肚。所以在生活中，一方面要缓解压力，善于释放压力，另一方面，要养成良好的生活习惯，不熬夜，不经常在外宵夜。</p>\n<p><img src=\"/uploads/images/20190505/fd89ab87e73f8aedc9a19894cabbd71b.jpg\" alt=\"\" /></p>\n<p><strong>3.长期坐立</strong></p>\n<p>啤酒肚常见于办公室人群，这是因为人们长期坐立，对能量的消耗变少，大腿根与腹股沟等部位会受到压迫，血流与淋巴无法流通，腹部的脂肪难以燃烧，慢慢的在腹部堆积起来，形成啤酒肚。</p>\n<p>我们所说的啤酒肚，主要并不是因为喝啤酒所形成，对于那些有啤酒肚的人来说，要赶快减掉啤酒肚，让自己有一个好的身体，健康的身体，那么怎么减啤酒肚呢？</p>\n<p>1.加强锻炼</p>\n<p>要减掉啤酒肚，加强锻炼必不可少。在平时可以多进行走路、游泳、爬山或者是跑步等运动，让自己动起来，脂肪燃烧起来，减轻体重，让腹部变小。</p>\n<p>2.注意饮食</p>\n<p>对于啤酒肚的人来说，减掉啤酒肚，要严格控制饮食，少吃高脂肪、高热量食物，多吃水果和蔬菜，每顿吃到七分饱。</p>\n<p>综合以上介绍，相信大家对于啤酒肚的知识有了比较深入的了解。啤酒肚的形成，主要原因并不是人们常说的喝了啤酒所导致，而是由于缺乏运动、长期久坐、生活没有规律，日积月累所形成的。啤酒肚一旦形成，除了影响美观外，还会影响到人体健康，因此，要通过运动、控制饮食等方式减掉啤酒肚，让自己有一个健康的身体。<img src=\"/uploads/images/20190505/0849832d0a591e2b19d936b61051becf.jpg\" alt=\"\" /></p>', '87', '50', 'admin', '2019', '1', '0', '1557036137', '1556985600');
INSERT INTO `pm_article` VALUES ('103', '2', '0-2-', '跑步若是一味追求速度和长度，健康将与你拒之千里', '', '', '', '', '', '/uploads/images/20190505/7cbfeca55f2246d922c9c8f415eee8eb_180_120.jpg,/uploads/images/20190505/2d12d8e297f9158a3bf756ad2f4b904a_180_120.jpg,/uploads/images/20190505/3a40be02dd7c643f38ac781ddfd7d620_180_120.jpg', '', '0', '0', '0', '0', '0', '走路、跑步是许多人锻炼身体，瘦身减肥的最常见运动方式，尤其是每天早晨，在公园、体育场馆，跑步锻炼的人群特别多。对于跑步，有的人认为跑的量越多越好，也有的人认为跑的越快越好，那么这些观点是否有道理，应该如何跑步才有减肥效果呢？\n\n跑步是不是越多越好\n\n\n\n对于跑步的量是不是越多越好，如何稍微有点科学常识，就知道，做任何事情都要量力而行，跑步并不是跑的越多就越好，而是要根据自身实际情况，适度跑步。过度跑步会导致肌肉损伤等情况，影响到身体健康。\n\n跑步是不是越快越好\n\n一些人认为跑步跑的越快，脂肪燃烧越多，效果越好，所以跑步要越快越好，这其实也是违背了量力而行的原则。想要通过跑步得到好的效果，一是要长期坚持，二是要合理控制速度，速度过快容易摔倒、拉伤肌肉。\n\n跑步的速度跟年龄、身体体质、心肺功能等有很大的关系，一个年轻人跑步的速度每小时10公里，但是对于老年人，这个速度就显得快了，因此，跑步的速度要根据年龄、身体状况等综合确定。\n\n\n\n对于经常跑步锻炼的人来说，在跑步时要控制好跑步的时间和速度，不能一味的求多求快。尤其对于那些通过跑步减肥的人群来说，要掌握跑步减肥的方法，让跑步有好的减肥效果', '<p>走路、跑步是许多人锻炼身体，瘦身减肥的最常见运动方式，尤其是每天早晨，在公园、体育场馆，跑步锻炼的人群特别多。对于跑步，有的人认为跑的量越多越好，也有的人认为跑的越快越好，那么这些观点是否有道理，应该如何跑步才有减肥效果呢？</p>\n<p><img src=\"/uploads/images/20190505/7cbfeca55f2246d922c9c8f415eee8eb.jpg\" alt=\"\" /></p>\n<p><strong>跑步是不是越多越好</strong></p>\n<p>对于跑步的量是不是越多越好，如何稍微有点科学常识，就知道，做任何事情都要量力而行，跑步并不是跑的越多就越好，而是要根据自身实际情况，适度跑步。过度跑步会导致肌肉损伤等情况，影响到身体健康。</p>\n<p><strong>跑步是不是越快越好</strong></p>\n<p>一些人认为跑步跑的越快，脂肪燃烧越多，效果越好，所以跑步要越快越好，这其实也是违背了量力而行的原则。想要通过跑步得到好的效果，一是要长期坚持，二是要合理控制速度，速度过快容易摔倒、拉伤肌肉。</p>\n<p>跑步的速度跟年龄、身体体质、心肺功能等有很大的关系，一个年轻人跑步的速度每小时10公里，但是对于老年人，这个速度就显得快了，因此，跑步的速度要根据年龄、身体状况等综合确定。</p>\n<p>对于经常跑步锻炼的人来说，在跑步时要控制好跑步的时间和速度，不能一味的求多求快。尤其对于那些通过跑步减肥的人群来说，要掌握跑步减肥的方法，让跑步有好的减肥效果，那么怎么跑步最减肥效果好呢？</p>\n<p><img src=\"/uploads/images/20190505/2d12d8e297f9158a3bf756ad2f4b904a.jpg\" alt=\"\" /></p>\n<p><strong>一、跑步准备活动要充分</strong></p>\n<p>跑步前做好准备活动，让身体进行预热，促进身体迅速适应跑步节奏，防止肌肉拉伤，当正式运动之前身体就快速消耗能量，对减肥有着很大的作用和效果。</p>\n<p><strong>二、跑步速度要快慢结合</strong></p>\n<p>跑步要有好的减肥效果，在速度上不能一成不变，要做到快慢结合，开始跑的时候慢跑几分钟，然后逐渐加速快跑，跑几分钟后再把速度减下来慢跑几分钟，这样快慢结合循环跑，减肥效果最好。</p>\n<p><img src=\"/uploads/images/20190505/3a40be02dd7c643f38ac781ddfd7d620.jpg\" alt=\"\" /></p>\n<p>以上为大家介绍了跑步是否跑的越多越好、越快越好的相关知识。</p>\n<p>通过上面的介绍，相信大家对于如何正确进行跑步的知识有了比较全面的了解，跑步的时间和跑步的速度要根据多个方面的因素来确定。而对于跑步减肥的人群来说，要想有好的减肥效果，还要注意正确的跑步方法。</p>', '93', '50', 'admin', '2019', '1', '0', '1557036679', '1556985600');

-- ----------------------------
-- Table structure for `pm_brand`
-- ----------------------------
DROP TABLE IF EXISTS `pm_brand`;
CREATE TABLE `pm_brand` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌表',
  `cid` int(11) NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '品牌logo',
  `desc` text NOT NULL COMMENT '品牌描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌地址',
  `sort` int(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `comm` tinyint(4) NOT NULL,
  `top` tinyint(4) NOT NULL,
  `py` varchar(10) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_brand
-- ----------------------------
INSERT INTO `pm_brand` VALUES ('10', '9', '可瑞康', '/uploads/images/20190808/998a9cf01b1c6625089e125c1958f710.jpg', '', '', '4', '0', '0', 'K', '1538452189', '1565261451');
INSERT INTO `pm_brand` VALUES ('3', '10', '佳思敏', '/uploads/images/20190808/b8bcb1974cb16db42778fab295484cf8.jpg', '', '', '7', '1', '0', 'J', '1534769273', '1565261333');
INSERT INTO `pm_brand` VALUES ('4', '9', 'A2', '/uploads/images/20190808/aec2051e04ad55a16e3c25ff54a82b81.jpg', '', '', '1', '1', '0', 'A', '1534912117', '1565261204');
INSERT INTO `pm_brand` VALUES ('5', '9', '爱他美', '/uploads/images/20190808/9808435c0c26e69f59c8854f421dfcb5.jpg', '', '', '2', '1', '0', 'A', '1534944898', '1565261319');
INSERT INTO `pm_brand` VALUES ('8', '12', 'Swisse', '/uploads/images/20190808/af859c302b49495917e95627e967309d.jpg', '', '', '1', '1', '1', 'S', '1536034392', '1565262195');
INSERT INTO `pm_brand` VALUES ('9', '12', '澳佳宝', '/uploads/images/20190808/67a264b22634a39a2b6cf82efc826bdc.jpg', '', '', '2', '1', '1', 'A', '1536034458', '1565261438');
INSERT INTO `pm_brand` VALUES ('11', '9', '贝拉米', '/uploads/images/20190808/8a7dac92334df36c47c105390336d986.jpg', '', '', '3', '1', '0', 'B', '1538452869', '1565261464');
INSERT INTO `pm_brand` VALUES ('12', '12', '爱乐维', '/uploads/images/20190808/ce8c7fed660f7b48f6748289437add1a.jpg', '', '', '10', '0', '0', 'A', '1538467354', '1565261477');
INSERT INTO `pm_brand` VALUES ('13', '16', '德运', '/uploads/images/20190808/559bff81ca0258a5b2e70d01169e291c.jpg', '', '', '15', '0', '0', 'D', '1538473320', '1565261492');
INSERT INTO `pm_brand` VALUES ('14', '10', 'GAIA', '/uploads/images/20190808/fb89635eb8b3f9897d371f5cb947d491.jpg', '', '', '17', '1', '1', 'G', '1538564972', '1565261509');
INSERT INTO `pm_brand` VALUES ('15', '8', 'QV', '/uploads/images/20190808/97f73e8bcb5b786169dc647c56feeaa1.jpg', '', '', '18', '1', '1', 'Q', '1538565237', '1565261526');
INSERT INTO `pm_brand` VALUES ('16', '16', '美可卓', '/uploads/images/20190808/683537b3b567ef3b466eceb01b8757e0.jpg', '', '', '9', '0', '1', 'M', '1540639709', '1565261544');
INSERT INTO `pm_brand` VALUES ('17', '9', '雅培', '/uploads/images/20190808/d758bed22e2af72acfcf4fb306a72981.png', '', '', '11', '0', '0', 'Y', '1540639801', '1565261562');
INSERT INTO `pm_brand` VALUES ('18', '10', '生物岛', '/uploads/images/20190808/4c16e0f1a404a3abed67cf977938e377.jpg', '', '', '4', '1', '1', 'S', '1540639875', '1565261589');
INSERT INTO `pm_brand` VALUES ('19', '8', '茱莉蔻', '/uploads/images/20190808/d86f0b1f8f750e278aa347d1a09c646f.jpg', '', '', '20', '0', '0', 'Z', '1540639898', '1565261599');
INSERT INTO `pm_brand` VALUES ('20', '7', '苏芊', '/uploads/images/20190808/1fd385f54cdef192fe470f4742ca05d5.jpg', '', '', '21', '0', '1', 'S', '1540639923', '1565261612');
INSERT INTO `pm_brand` VALUES ('46', '8', '其他护肤品', '/uploads/images/20190220/6bcde67667030c6f45aaba1fb26b5379.jpg', '', '', '50', '0', '0', 'Q', '1547187665', '1564993809');
INSERT INTO `pm_brand` VALUES ('47', '12', '安瓶', '/uploads/images/20190808/5258c35b8fb2ac3a2acc144be874e90b.png', '', '', '50', '0', '1', 'A', '1547428823', '1565261966');
INSERT INTO `pm_brand` VALUES ('22', '12', 'Healthy Care', '/uploads/images/20190808/f0ddcf35303b378977b257621ce51df1.png', '', '', '3', '1', '1', 'H', '1540639967', '1565261627');
INSERT INTO `pm_brand` VALUES ('23', '8', '百洛油', '/uploads/images/20190808/32d2bcd24694a756f3225e1a6803de7a.jpg', '', '', '28', '0', '1', 'B', '1540639992', '1565261639');
INSERT INTO `pm_brand` VALUES ('24', '7', '星期四', '/uploads/images/20190808/e2328ee00144c3fdb25af4817e102287.jpg', '', '', '22', '1', '1', 'X', '1540640023', '1565261655');
INSERT INTO `pm_brand` VALUES ('25', '7', '水光针', '/uploads/images/20190808/1e3e6cbb87a539b86fe2d8431e316d23.jpg', '', '', '9', '1', '1', 'S', '1540640037', '1565261680');
INSERT INTO `pm_brand` VALUES ('26', '14', '康维他', '/uploads/images/20190808/b4a92f24314dd0c623db3c8f2529095d.jpg', '', '', '17', '0', '0', 'K', '1540640117', '1565261712');
INSERT INTO `pm_brand` VALUES ('27', '12', '乐康膏', '/uploads/images/20181026/10ad165896fee374798c7491cda80fb7.jpg', '', '', '16', '0', '1', 'L', '1540640200', '1564994071');
INSERT INTO `pm_brand` VALUES ('28', '18', '奔富', '/uploads/images/20190808/8b0fb2daf8dd8b648f5803ad7a9ddec3.jpg', '', '', '23', '0', '0', 'B', '1540640223', '1565261729');
INSERT INTO `pm_brand` VALUES ('31', '10', 'Jack n’jill', '/uploads/images/20190808/3dc26e05d323f765d0ffd8bbf3dddcf1.png', '', '', '26', '0', '1', 'J', '1540640341', '1565261744');
INSERT INTO `pm_brand` VALUES ('32', '18', '木瓜膏', '/uploads/images/20190808/a1cc9ddc235b480b84d6468dff15ff78.jpg', '', '', '5', '1', '1', 'M', '1540640364', '1565261763');
INSERT INTO `pm_brand` VALUES ('33', '10', '贝博士', '/uploads/images/20190808/8f0454cc287de8bdf52b42ec01610913.jpg', '', '', '12', '1', '1', 'B', '1540698631', '1565261777');
INSERT INTO `pm_brand` VALUES ('34', '12', '益生菌', '/uploads/images/20190808/97406c8fcb1afbfc98cbf0859c33de81.jpg', '', '', '6', '1', '0', 'Y', '1540698792', '1565261794');
INSERT INTO `pm_brand` VALUES ('35', '16', '澳美滋', '/uploads/images/20190808/762fd476e86973328b6bf9eda9c83c1e.jpg', '', '', '12', '0', '0', 'A', '1540698983', '1565261812');
INSERT INTO `pm_brand` VALUES ('36', '12', '酵素', '/uploads/images/20190808/3d956ef31f947b3e7a173856ffaf46cb.jpg', '', '', '18', '0', '1', 'J', '1540699142', '1565261827');
INSERT INTO `pm_brand` VALUES ('37', '9', '惠氏', '/uploads/images/20190808/f7ea88bb8f1d32f62071d6ec7ba0db06.jpg', '', '', '50', '0', '0', 'H', '1544592863', '1565261840');
INSERT INTO `pm_brand` VALUES ('38', '9', '满趣健 草饲', '/uploads/images/20190808/0bfec8bd033e199239e1570475d8b986.jpg', '', '', '50', '1', '0', 'M', '1544595668', '1565261854');
INSERT INTO `pm_brand` VALUES ('39', '16', 'CapriLac', '/uploads/images/20190808/23ad532a4eaa0cb46f94f17c0bbc238c.png', '', '', '50', '0', '0', 'C', '1544605520', '1565261865');
INSERT INTO `pm_brand` VALUES ('40', '9', '雀巢', '/uploads/images/20190808/ff7919dfed42ec26d3e49e34316930e5.jpg', '', '', '50', '0', '0', 'Q', '1544605982', '1565261879');
INSERT INTO `pm_brand` VALUES ('41', '8', '艾维诺', '/uploads/images/20190808/af92ad9bab729c75d49fee150e359095.jpg', '', '', '50', '0', '0', 'A', '1544845875', '1565261893');
INSERT INTO `pm_brand` VALUES ('42', '14', '新溪岛', '/uploads/images/20190808/15a8acec0f72227c9994fc9949e65d37.jpg', '', '', '50', '0', '0', 'X', '1544850429', '1565261908');
INSERT INTO `pm_brand` VALUES ('43', '7', 'NATIO', '/uploads/images/20190808/4917c4666130ff09cb59b1bb53d4ab38.jpg', '', '', '16', '1', '1', 'N', '1544852395', '1565261920');
INSERT INTO `pm_brand` VALUES ('44', '7', 'Antipodes', '/uploads/images/20190808/4131299a6ca70dba7f321047bb5bef99.jpg', '', '', '50', '0', '0', 'A', '1544853044', '1565261934');
INSERT INTO `pm_brand` VALUES ('45', '18', '红印', '/uploads/images/20190808/641450a9e2cc0ee8ecff3f0aa3f0ca9c.jpg', '', '', '50', '0', '0', 'H', '1545378010', '1565261948');
INSERT INTO `pm_brand` VALUES ('48', '8', 'Goat 羊奶系列', '/uploads/images/20190808/dd03166a63e3cf57ae6d696fdff75d83.jpg', '', '', '10', '1', '1', 'G', '1547430101', '1565261978');
INSERT INTO `pm_brand` VALUES ('49', '7', 'Trilogy', '/uploads/images/20190808/5fe7a87303cd049a6478089650d44361.png', '', '', '50', '0', '0', 'T', '1547430311', '1565261994');
INSERT INTO `pm_brand` VALUES ('50', '12', '其他保健品', '/uploads/images/20190220/1624a067359cd1bc26e19e0d8bddafd7.jpg', '', '', '50', '0', '0', 'Q', '1547430357', '1564993759');
INSERT INTO `pm_brand` VALUES ('51', '8', 'Freezeframe', '/uploads/images/20190808/38e18bb6fe2a4ea7a07028a46c7d6501.jpg', '', '', '15', '1', '1', 'F', '1547430592', '1565262019');
INSERT INTO `pm_brand` VALUES ('52', '12', 'UNICHI', '/uploads/images/20190808/0df46cbd24c51032b7f626f6ca02d262.jpg', '', '', '14', '1', '1', 'U', '1547431459', '1565262032');
INSERT INTO `pm_brand` VALUES ('53', '17', '食品类', '/uploads/images/20190808/365fad6127de4d729f02e79798988aaf.jpg', '', '', '50', '0', '0', 'S', '1547431743', '1565262238');
INSERT INTO `pm_brand` VALUES ('54', '18', '其他日用品', '/uploads/images/20190220/87fd24a1c271352d532297d0eca15c6a.jpg', '', '', '50', '0', '0', 'Q', '1547432489', '1564993717');
INSERT INTO `pm_brand` VALUES ('55', '12', '奥斯特林', '/uploads/images/20190808/cfa8502a0a715e5bc347f996ac88bbfe.jpg', '', '', '8', '1', '1', 'A', '1547432656', '1565262047');
INSERT INTO `pm_brand` VALUES ('56', '18', '康迪克', '/uploads/images/20190808/ebf2ffd5edbf90f1aaf98715d500f6f1.jpg', '', '', '50', '0', '0', 'K', '1547432782', '1565262059');
INSERT INTO `pm_brand` VALUES ('57', '8', 'Oral-B', '/uploads/images/20190808/8249f4e70d12096928ca3e7a93541af9.jpg', '', '', '50', '0', '0', 'O', '1547432908', '1565262071');
INSERT INTO `pm_brand` VALUES ('58', '18', 'Jellycat', '/uploads/images/20190808/b0cdcc9081892b1ed9955bca3816d934.jpg', '', '', '19', '1', '1', 'J', '1550234263', '1565262084');
INSERT INTO `pm_brand` VALUES ('61', '9', 'OLI羊奶粉', '/uploads/images/20190808/67667424cf1d8bd445f2ebaca0056a19.jpg', '', '', '50', '0', '0', 'O', '1552970449', '1565262116');
INSERT INTO `pm_brand` VALUES ('59', '8', 'GM', '/uploads/images/20190808/c4e086b1c3e2687d5d8862a55a4a62a9.jpg', '', '', '11', '1', '1', 'G', '1552271717', '1565262095');
INSERT INTO `pm_brand` VALUES ('60', '8', 'Femfresh', '/uploads/images/20190808/d4782bb329ee1c851620c055c14ff485.jpg', '', '', '13', '1', '1', 'F', '1552271764', '1565262105');
INSERT INTO `pm_brand` VALUES ('62', '9', 'Bubs贝儿羊奶粉', '/uploads/images/20190808/e8832546f5f684e95933f93e3d9db55b.jpg', '', '', '50', '1', '0', 'B', '1554945914', '1565262127');
INSERT INTO `pm_brand` VALUES ('63', '16', 'Diploma学生奶粉', '/uploads/images/20190808/1fa2d5188724a35b0cbb06ea6a4b3d22.jpg', '', '', '50', '0', '0', 'D', '1554947900', '1565262137');

-- ----------------------------
-- Table structure for `pm_cart`
-- ----------------------------
DROP TABLE IF EXISTS `pm_cart`;
CREATE TABLE `pm_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `shopID` int(11) NOT NULL,
  `goodsID` int(11) NOT NULL,
  `specID` int(11) NOT NULL COMMENT '商品规格',
  `number` int(11) NOT NULL,
  `trueNumber` int(11) NOT NULL COMMENT '真实商品数量比如2个3件的套餐就显示6',
  `typeID` int(11) NOT NULL COMMENT '包裹类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_cart
-- ----------------------------
INSERT INTO `pm_cart` VALUES ('19', '2', '0', '10', '0', '1', '1', '1');
INSERT INTO `pm_cart` VALUES ('18', '2', '0', '19', '0', '1', '1', '1');
INSERT INTO `pm_cart` VALUES ('20', '2', '0', '20', '0', '3', '3', '4');

-- ----------------------------
-- Table structure for `pm_category`
-- ----------------------------
DROP TABLE IF EXISTS `pm_category`;
CREATE TABLE `pm_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` int(2) NOT NULL COMMENT '所属模型',
  `fid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '9999',
  `path` varchar(200) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `num` int(11) NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_category
-- ----------------------------
INSERT INTO `pm_category` VALUES ('1', '6', '0', '首页广告', '', '50', '0-1-', '', '', '0', '', '', '1563116411', '1563116411');
INSERT INTO `pm_category` VALUES ('2', '6', '0', '品牌墙广告', '', '50', '0-2-', '', '', '0', '', '', '1565969443', '1565969443');

-- ----------------------------
-- Table structure for `pm_city`
-- ----------------------------
DROP TABLE IF EXISTS `pm_city`;
CREATE TABLE `pm_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_city
-- ----------------------------
INSERT INTO `pm_city` VALUES ('1', '阿德莱德', '1566486859', '1566615660');
INSERT INTO `pm_city` VALUES ('2', '悉尼', '1566486870', '1566615951');

-- ----------------------------
-- Table structure for `pm_city_express`
-- ----------------------------
DROP TABLE IF EXISTS `pm_city_express`;
CREATE TABLE `pm_city_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cityID` int(11) DEFAULT NULL,
  `expressID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_city_express
-- ----------------------------
INSERT INTO `pm_city_express` VALUES ('10', '1', '3');
INSERT INTO `pm_city_express` VALUES ('9', '1', '2');
INSERT INTO `pm_city_express` VALUES ('8', '1', '1');
INSERT INTO `pm_city_express` VALUES ('12', '2', '2');
INSERT INTO `pm_city_express` VALUES ('11', '2', '1');

-- ----------------------------
-- Table structure for `pm_config`
-- ----------------------------
DROP TABLE IF EXISTS `pm_config`;
CREATE TABLE `pm_config` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `name` varchar(64) DEFAULT NULL COMMENT '配置的key键名',
  `value` varchar(512) DEFAULT NULL COMMENT '配置的val值',
  `inc_type` varchar(64) DEFAULT NULL COMMENT '配置分组',
  `desc` varchar(50) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_config
-- ----------------------------
INSERT INTO `pm_config` VALUES ('1', 'name', '途买商城', 'basic', '');
INSERT INTO `pm_config` VALUES ('2', 'logo', '', 'basic', '');
INSERT INTO `pm_config` VALUES ('3', 'isClose', '0', 'basic', '');
INSERT INTO `pm_config` VALUES ('4', 'closeInfo', '系统维护中', 'basic', '');
INSERT INTO `pm_config` VALUES ('5', 'domain', 'http://127.0.0.9', 'basic', '');
INSERT INTO `pm_config` VALUES ('6', 'copyright', '途买商城', 'basic', '');
INSERT INTO `pm_config` VALUES ('7', 'email', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('8', 'weixin', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('9', 'weibo', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('10', 'description', '途买商城', 'basic', '');
INSERT INTO `pm_config` VALUES ('11', 'qrcode', '', 'basic', '');
INSERT INTO `pm_config` VALUES ('19', 'mobile', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('12', 'address', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('13', 'tel', '010-23190228', 'basic', '');
INSERT INTO `pm_config` VALUES ('15', 'qq', '1826366140', 'basic', '');
INSERT INTO `pm_config` VALUES ('16', 'keywords', '途买商城', 'basic', '');
INSERT INTO `pm_config` VALUES ('18', 'title', '途买商城', 'basic', '');
INSERT INTO `pm_config` VALUES ('82', 'sign', '10', 'member', null);
INSERT INTO `pm_config` VALUES ('21', 'safecode', '123456', 'basic', '');
INSERT INTO `pm_config` VALUES ('83', 'huilv', '4.29', 'member', null);
INSERT INTO `pm_config` VALUES ('84', 'orderTime', '48', 'member', null);
INSERT INTO `pm_config` VALUES ('85', 'register', '50', 'member', null);
INSERT INTO `pm_config` VALUES ('86', 'buy', '1', 'member', null);
INSERT INTO `pm_config` VALUES ('87', 'jifen1', '0', 'member', null);
INSERT INTO `pm_config` VALUES ('88', 'back1', '5', 'member', null);
INSERT INTO `pm_config` VALUES ('89', 'jifen2', '3000', 'member', null);
INSERT INTO `pm_config` VALUES ('90', 'back2', '10', 'member', null);
INSERT INTO `pm_config` VALUES ('91', 'jifen3', '6000', 'member', null);
INSERT INTO `pm_config` VALUES ('92', 'back3', '15', 'member', null);
INSERT INTO `pm_config` VALUES ('38', 'isSms', '1', 'sms', '');
INSERT INTO `pm_config` VALUES ('39', 'sms_name', 'xinfeidianqi', 'sms', '');
INSERT INTO `pm_config` VALUES ('40', 'sms_pwd', 'kf01888', 'sms', '');
INSERT INTO `pm_config` VALUES ('41', 'sms_sign', '奥讯', 'sms', '');
INSERT INTO `pm_config` VALUES ('42', 'out_time', '10', 'sms', '');
INSERT INTO `pm_config` VALUES ('43', 'diffTime', '1', 'sms', '');
INSERT INTO `pm_config` VALUES ('44', 'dayNumber', '5', 'sms', '');
INSERT INTO `pm_config` VALUES ('93', 'jifen4', '9000', 'member', null);
INSERT INTO `pm_config` VALUES ('94', 'back4', '20', 'member', null);
INSERT INTO `pm_config` VALUES ('95', 'jifen5', '12000', 'member', null);
INSERT INTO `pm_config` VALUES ('50', 'APP_TOKEN', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('51', 'APP_ID', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('52', 'APP_SECRET', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('53', 'MCH_KEY', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('54', 'MCH_ID', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('55', 'NOTIFY', '#', 'alipay', null);
INSERT INTO `pm_config` VALUES ('56', 'ALIPAY_EMAIL', '491623529@qq22.com', 'alipay', null);
INSERT INTO `pm_config` VALUES ('57', 'ALIPAY_KEY', 'z0kn76wfr4e6c7ppgxdo4nnx5qwuk459', 'alipay', null);
INSERT INTO `pm_config` VALUES ('58', 'ALIPAY_PARTNER', '2088921795656107', 'alipay', null);
INSERT INTO `pm_config` VALUES ('59', 'NOTIFY', '#', 'alipay', null);
INSERT INTO `pm_config` VALUES ('96', 'back5', '25', 'member', null);
INSERT INTO `pm_config` VALUES ('63', 'linkman', '#', 'basic', null);
INSERT INTO `pm_config` VALUES ('64', 'fax', '0371-23190098', 'basic', null);
INSERT INTO `pm_config` VALUES ('97', 'min', '0.01', 'member', null);
INSERT INTO `pm_config` VALUES ('98', 'max', '2.00', 'member', null);
INSERT INTO `pm_config` VALUES ('99', 'hour', '24', 'member', null);
INSERT INTO `pm_config` VALUES ('100', 'shareMax', '20', 'member', null);
INSERT INTO `pm_config` VALUES ('101', 'isReg', '1', 'member', null);
INSERT INTO `pm_config` VALUES ('102', 'hotkey', '保湿面膜,发发发,fasdfasdf', 'member', null);
INSERT INTO `pm_config` VALUES ('103', 'kuaidi12', '红酒专邮', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('104', 'price12', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('105', 'inprice12', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('106', 'otherPrice12', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('107', 'url12', 'http://api.transrush.com.au/cms/', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('108', 'kuaidi13', '面单专邮', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('109', 'price13', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('110', 'inprice13', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('111', 'otherPrice13', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('112', 'url13', 'http://www.kuaidi100.com/', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('113', 'kuaidi14', '生鲜专邮', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('114', 'price14', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('115', 'inprice14', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('116', 'otherPrice14', '0', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('117', 'url14', 'http://www.sf-express.com/cn/sc/', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('118', 'price1', '4.3', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('119', 'inprice1', '3.5', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('120', 'otherPrice1', '2', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('121', 'price2', '10', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('122', 'inprice2', '7.5', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('123', 'otherPrice2', '2', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('124', 'price3', '6', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('125', 'inprice3', '5.6', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('126', 'otherPrice3', '2', 'kuaidi', null);
INSERT INTO `pm_config` VALUES ('127', 'file', '', 'basic', null);
INSERT INTO `pm_config` VALUES ('128', 'flashTime', '8:00-22:59', 'member', null);
INSERT INTO `pm_config` VALUES ('129', 'isCut', '1', 'member', null);
INSERT INTO `pm_config` VALUES ('130', 'OMI_ID', '501039', 'omi', null);
INSERT INTO `pm_config` VALUES ('131', 'OMI_KEY', '547e766b244a4e7eb24fee84a1e28fd2', 'omi', null);

-- ----------------------------
-- Table structure for `pm_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `pm_coupon`;
CREATE TABLE `pm_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `shopName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `register` int(11) NOT NULL,
  `full` int(11) NOT NULL,
  `dec` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `number` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `goodsID` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updateTime` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pm_coupon
-- ----------------------------
INSERT INTO `pm_coupon` VALUES ('3', '8', '奶粉专卖店', '新手券', '立减50元', '1', '0', '50', '1', '1', '30', '', '使用说明啊啊啊', '1567501388', '1551964995');
INSERT INTO `pm_coupon` VALUES ('5', '7', '测试店铺', '测试一下', '满50元立减5元', '0', '50', '5', '0', '1', '30', '', '', '1566741166', '1565698449');
INSERT INTO `pm_coupon` VALUES ('6', '7', '测试店铺', '123123', '立减1元', '0', '0', '1', '0', '1', '1', '', '', '1567501406', '1567501406');

-- ----------------------------
-- Table structure for `pm_coupon_goods`
-- ----------------------------
DROP TABLE IF EXISTS `pm_coupon_goods`;
CREATE TABLE `pm_coupon_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `couponID` int(11) NOT NULL,
  `goodsID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_coupon_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_coupon_log`
-- ----------------------------
DROP TABLE IF EXISTS `pm_coupon_log`;
CREATE TABLE `pm_coupon_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) DEFAULT NULL,
  `memberID` int(11) DEFAULT NULL,
  `nickname` varchar(200) DEFAULT NULL,
  `couponID` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `full` decimal(8,2) DEFAULT NULL,
  `dec` decimal(5,2) DEFAULT NULL,
  `intr` text,
  `goodsID` varchar(200) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL COMMENT '编号',
  `status` tinyint(4) DEFAULT NULL COMMENT '0未使用 1已使用',
  `useTime` int(11) DEFAULT NULL,
  `endTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_coupon_log
-- ----------------------------
INSERT INTO `pm_coupon_log` VALUES ('18', null, '2', '张小黑', '3', '新手券', '立减50元', '0.00', '50.00', null, '', '1277665138', '0', '0', '1568423272', '1565831272');
INSERT INTO `pm_coupon_log` VALUES ('14', null, '0', '', '5', '测试一下', '满50元立减5元', '0.00', '5.00', null, '', '1264725452', '0', '0', '0', '1565798942');
INSERT INTO `pm_coupon_log` VALUES ('15', null, '0', '', '5', '测试一下', '满50元立减5元', '0.00', '5.00', null, '', '1189652748', '0', '0', '0', '1565798942');
INSERT INTO `pm_coupon_log` VALUES ('16', '8', '10002', '月明', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '好吃不贵', '', '1355403368', '1', '1567568588', '1568823326', '1565798942');
INSERT INTO `pm_coupon_log` VALUES ('17', null, '2', '张小黑', '5', '测试一下', '满50元立减5元', '0.00', '5.00', null, '240', '1039757623', '0', '0', '1568391156', '1565799156');
INSERT INTO `pm_coupon_log` VALUES ('19', '7', '10002', '月明', '3', '新手券', '立减3元', '0.00', '3.00', '好贵不吃', '', '1239757623', '1', '1567568588', '1568809414', '1566217414');

-- ----------------------------
-- Table structure for `pm_express`
-- ----------------------------
DROP TABLE IF EXISTS `pm_express`;
CREATE TABLE `pm_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_express
-- ----------------------------
INSERT INTO `pm_express` VALUES ('1', '澳邮', '1566610883', '1566610883');
INSERT INTO `pm_express` VALUES ('2', '京东', '1566611032', '1566611032');
INSERT INTO `pm_express` VALUES ('3', '中环', '1566611043', '1566611043');

-- ----------------------------
-- Table structure for `pm_fav`
-- ----------------------------
DROP TABLE IF EXISTS `pm_fav`;
CREATE TABLE `pm_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_fav
-- ----------------------------
INSERT INTO `pm_fav` VALUES ('4', '20', '2');
INSERT INTO `pm_fav` VALUES ('5', '24', '2');
INSERT INTO `pm_fav` VALUES ('6', '18', '2');
INSERT INTO `pm_fav` VALUES ('7', '15', '2');
INSERT INTO `pm_fav` VALUES ('15', '19', '10001');
INSERT INTO `pm_fav` VALUES ('16', '9', '10001');
INSERT INTO `pm_fav` VALUES ('17', '11', '10001');

-- ----------------------------
-- Table structure for `pm_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `pm_feedback`;
CREATE TABLE `pm_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `memberID` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `reply` text NOT NULL,
  `content` text NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_finance`
-- ----------------------------
DROP TABLE IF EXISTS `pm_finance`;
CREATE TABLE `pm_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL COMMENT '会员iD',
  `admin` int(11) NOT NULL COMMENT '1管理员 2普通会员',
  `doID` int(11) NOT NULL COMMENT '操作者ID',
  `type` int(11) NOT NULL COMMENT '类型',
  `money` decimal(10,2) NOT NULL COMMENT '交易金额',
  `oldMoney` decimal(10,2) NOT NULL,
  `newMoney` decimal(10,2) NOT NULL,
  `msg` varchar(300) NOT NULL,
  `extend1` int(11) NOT NULL,
  `extend2` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_finance
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_flash`
-- ----------------------------
DROP TABLE IF EXISTS `pm_flash`;
CREATE TABLE `pm_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `goodsID` int(11) NOT NULL,
  `goodsName` varchar(200) NOT NULL,
  `price` varchar(50) NOT NULL,
  `spec` text NOT NULL,
  `pack` text NOT NULL,
  `startDate` int(11) NOT NULL,
  `endDate` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_flash
-- ----------------------------
INSERT INTO `pm_flash` VALUES ('12', '15', '9', 'A2铂金 一段 A2 Infant Formula ', '26', 'a:0:{}', 'a:0:{}', '1564329600', '1567094399', '999', '1564396835');
INSERT INTO `pm_flash` VALUES ('19', '10', '22', 'Elevit 女士爱乐维 孕期维生素 100粒', '45', 'a:0:{}', 'a:0:{}', '1567267200', '1567871999', '999', '1567340460');
INSERT INTO `pm_flash` VALUES ('15', '16', '17', '爱他美白金 二段 Aptamil Profutura Follow On Formula', '35', 'a:0:{}', 'a:0:{}', '1564329600', '1567267199', '999', '1564402509');
INSERT INTO `pm_flash` VALUES ('16', '10', '20', 'Blackmores澳佳宝 孕妇黄金素 180粒', '28', 'a:0:{}', 'a:0:{}', '1564502400', '1567267199', '999', '1564829065');
INSERT INTO `pm_flash` VALUES ('18', '4', '23', 'Swisse 高倍蜂胶2000mg 300粒', '23', 'a:0:{}', 'a:0:{}', '1564502400', '1567180799', '999', '1564829752');

-- ----------------------------
-- Table structure for `pm_fund`
-- ----------------------------
DROP TABLE IF EXISTS `pm_fund`;
CREATE TABLE `pm_fund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) DEFAULT NULL,
  `money` varchar(20) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_fund
-- ----------------------------
INSERT INTO `pm_fund` VALUES ('1', '2019-08', '4000', '1565839509');
INSERT INTO `pm_fund` VALUES ('2', '2019-09', '0', '1565839927');

-- ----------------------------
-- Table structure for `pm_goods`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods`;
CREATE TABLE `pm_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `shopName` varchar(200) NOT NULL,
  `cityID` int(11) NOT NULL,
  `expressID` int(11) NOT NULL,
  `payment` decimal(4,2) NOT NULL COMMENT '物流单价/公斤',
  `fid` int(11) NOT NULL COMMENT '套餐对应的商品ID，0没有套餐',
  `cid` int(11) NOT NULL,
  `path` varchar(50) NOT NULL,
  `cid1` int(11) NOT NULL,
  `path1` varchar(255) DEFAULT NULL,
  `typeID` int(11) NOT NULL COMMENT '商品类型ID 1奶粉2保健品3护肤品',
  `modelID` int(11) NOT NULL COMMENT '模型的ID',
  `brandID` int(11) NOT NULL COMMENT '品牌ID',
  `name` varchar(200) NOT NULL,
  `en` varchar(200) NOT NULL,
  `short` varchar(100) NOT NULL,
  `say` varchar(50) NOT NULL,
  `keyword` text NOT NULL,
  `intr` varchar(1000) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `image` text,
  `content` text NOT NULL,
  `endDate` varchar(50) NOT NULL,
  `point` int(11) NOT NULL COMMENT '购物积分',
  `tjPoint` int(11) NOT NULL COMMENT '分销积分',
  `inprice` decimal(10,2) NOT NULL COMMENT '进价=结算价*(100-服务费)/100',
  `price` decimal(10,2) NOT NULL COMMENT '售价',
  `servePrice` decimal(10,2) NOT NULL COMMENT '服务费%',
  `jiesuan` decimal(10,2) NOT NULL COMMENT 'j结算价',
  `gst` decimal(4,2) NOT NULL COMMENT '含税税率',
  `weight` decimal(10,2) NOT NULL,
  `wuliuWeight` decimal(10,2) NOT NULL,
  `sellNumber` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `number` int(11) NOT NULL COMMENT '单品的数量，如果是3件s商品的套餐，就填写3',
  `comm` tinyint(4) NOT NULL COMMENT '是否推荐',
  `baoyou` tinyint(4) NOT NULL,
  `flash` tinyint(4) NOT NULL,
  `tehui` tinyint(4) NOT NULL,
  `show` tinyint(11) NOT NULL COMMENT '是否显示',
  `sort` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods
-- ----------------------------
INSERT INTO `pm_goods` VALUES ('7', '8', '奶粉专卖店', '2', '1', '0.00', '0', '15', '0-1-15-', '0', '', '1', '0', '4', 'A2铂金 三段 A2 Premium Toddler', 'A2 Premium Toddler Stage 3 900g', 'A3', '澳洲最佳奶粉推荐，独特a2配方', '', 'A2 Platinum白金系列高端牛奶粉是专门为婴幼儿而设计的特殊配方奶粉，它的营养很全面，能为宝宝提供成长和发育所需要的重要营养成分。该独特的配方奶粉含有天然的A2牛奶，能促进宝宝消化系统的发育,丰富的营养成分，有助于宝宝大脑和眼睛的发育，促进宝宝免疫系统的发育。 三段：1岁', '/uploads/images/20190729/9609c47af27df3c4875605abde9ff4dc.jpg', null, '', '2020/10', '30', '2', '25.00', '31.00', '35.00', '31.00', '0.00', '1.10', '1.10', '200', '999', '1', '1', '0', '0', '0', '1', '50', '1563703331', '1567475291');
INSERT INTO `pm_goods` VALUES ('10', '8', '奶粉专卖店', '2', '1', '0.00', '0', '15', '0-1-15-', '0', '', '1', '0', '4', 'A2铂金 二段 A2 Follow On Formula', 'A2 Follow On Formula Stage 2 900g', 'A2-2', '澳洲最佳奶粉推荐，独特a2配方', '', 'A2 Platinum白金系列高端牛奶粉是专门为婴幼儿而设计的特殊配方奶粉，它的营养很全面，能为宝宝提供成长和发育所需要的重要营养成分。该独特的配方奶粉含有天然的A2牛奶，能促进宝宝消化系统的发育,丰富的营养成分，有助于宝宝大脑和眼睛的发育，促进宝宝免疫系统的发育。 二段：6-12月', '/uploads/images/20190729/e47cfa2678fc5785d957bb71e4e19f6e.jpg', null, '', '2020/10', '30', '2', '25.00', '29.00', '35.00', '3.00', '0.00', '1.10', '1.10', '100', '999', '1', '0', '0', '0', '0', '1', '50', '1564396584', '1567475308');
INSERT INTO `pm_goods` VALUES ('9', '8', '奶粉专卖店', '2', '2', '0.00', '0', '15', '0-1-15-', '0', '', '1', '0', '4', 'A2铂金 一段 A2 Infant Formula ', 'A2 Infant Formula Stage 1 900g', 'A1', '澳洲最佳奶粉推荐', '', 'A2 Platinum白金系列高端牛奶粉一段是专门为新生儿而设计的特殊配方奶粉，它的营养很全面，能为宝宝提供成长和发育所需要的重要营养成分。该独特的配方奶粉含有天然的A2牛奶，能促进宝宝消化系统的发育,丰富的营养成分，有助于宝宝大脑和眼睛的发育，促进宝宝免疫系统的发育。 一段：0-6月', '/uploads/images/20190729/a0312cfa0da34943a7898ee675267dd3.jpg', null, '', '2020/10', '30', '1', '25.00', '30.00', '35.00', '30.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564395464', '1567475300');
INSERT INTO `pm_goods` VALUES ('11', '8', '奶粉专卖店', '2', '1', '0.00', '0', '15', '0-1-15-', '0', '', '1', '0', '4', 'A2铂金 四段 A2 Junior Formula', 'A2 Junior Stage 4 900g', 'A4', '澳洲最佳奶粉推荐，独特a2配方', '', 'A2 Platinum白金系列高端牛奶粉是专门为婴幼儿而设计的特殊配方奶粉，它的营养很全面，能为宝宝提供成长和发育所需要的重要营养成分。该独特的配方奶粉含有天然的A2牛奶，能促进宝宝消化系统的发育,丰富的营养成分，有助于宝宝大脑和眼睛的发育，促进宝宝免疫系统的发育。 四段：3岁', '/uploads/images/20190729/aa51a8e0127330992820424d3613f9db.jpg', null, '', '2020/10', '34', '2', '25.00', '34.00', '36.00', '34.00', '0.00', '1.10', '1.10', '100', '999', '1', '0', '0', '0', '0', '1', '50', '1564398209', '1567475318');
INSERT INTO `pm_goods` VALUES ('12', '7', '测试店铺', '1', '2', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美金装 一段 Aptamil Gold+ 1 Infant Formula', 'Aptamil Gold+ 1 Infant Formula 0-6 Months 900g', 'K1', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/0b2e9e8b22809180d74ab02aa7bae6ec.jpg', null, '', '2020/10', '28', '4', '25.00', '28.00', '35.00', '28.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564400481', '1564400496');
INSERT INTO `pm_goods` VALUES ('13', '7', '测试店铺', '1', '3', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美金装 二段 Aptamil Gold+ 2 Follow-On Formula', 'Aptamil Gold+ 2 Follow-On Formula 6-12 Months 900g', 'K2', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/88b995c3c31e9bd27f99690c5b8df15b.jpg', null, '', '2020/10', '27', '1', '25.00', '27.00', '30.00', '27.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564400592', '1564400592');
INSERT INTO `pm_goods` VALUES ('14', '7', '测试店铺', '1', '1', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美金装 三段 Aptamil Gold+ 3 Toddler', 'Aptamil Gold+ 3 Toddler Nutritional Supplement From 1 year 900g', 'K3', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/44e9f48967390b811d34b29ef63b1cc6.jpg', null, '', '2020/10', '30', '2', '22.00', '30.00', '36.00', '30.00', '0.00', '1.10', '1.10', '100', '999', '1', '0', '0', '0', '0', '1', '50', '1564400696', '1564400866');
INSERT INTO `pm_goods` VALUES ('15', '7', '测试店铺', '1', '1', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美金装 四段 Aptamil Gold+ 4 Junior', 'Aptamil Gold+ 4 Junior Nutritional Supplement From 2 years 900g', 'K4', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/0a9d834dbc1f306bd29cda25ad99bb7c.jpg', null, '', '2020/10', '23', '3', '20.00', '23.00', '25.00', '23.00', '0.00', '1.10', '1.10', '1000', '999', '1', '0', '0', '0', '0', '1', '50', '1564400804', '1564402845');
INSERT INTO `pm_goods` VALUES ('16', '7', '测试店铺', '1', '3', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美白金 一段 Aptamil Profutura Infant Formula', 'Aptamil Profutura Infant Formula 0-6 months 900g', 'P1', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/01a8d0cc2d792ead23003d07e611fc43.jpg', null, '', '2020/10', '36', '1', '23.00', '36.00', '40.00', '36.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564401252', '1564401252');
INSERT INTO `pm_goods` VALUES ('17', '7', '测试店铺', '1', '2', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美白金 二段 Aptamil Profutura Follow On Formula', 'Aptamil Profutura Follow On Formula 6-12 months 900g', 'P2', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/7b270e564d908413b1d1869720bfacfb.jpg', null, '', '2020/10', '36', '4', '22.00', '36.00', '40.00', '36.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564401367', '1564401381');
INSERT INTO `pm_goods` VALUES ('18', '7', '测试店铺', '1', '1', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美白金 三段 Aptamil Profutura Toddler ', 'Aptamil Profutura Toddler Nutritional Supplement From 1 year 900g', 'P3', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/812ed9ab28f0604af6a2045e43b71195.jpg', null, '', '2020/10', '25', '2', '20.00', '25.00', '30.00', '25.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564401466', '1564401478');
INSERT INTO `pm_goods` VALUES ('19', '7', '测试店铺', '1', '2', '0.00', '0', '16', '0-1-16-', '0', '', '1', '0', '5', '爱他美白金 四段 Aptamil Profutura Junior', 'Aptamil Profutura Junior Nutritional Supplement 900g', 'P4', '新西兰纯天然奶源', '', '', '/uploads/images/20190729/e22295c6db7b698255173d0d76b0b569.jpg', null, '', '2020/10', '280', '5', '20.00', '28.00', '32.00', '28.00', '0.00', '1.10', '1.10', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564401569', '1565883828');
INSERT INTO `pm_goods` VALUES ('20', '7', '测试店铺', '1', '1', '0.00', '0', '19', '0-10-19-', '0', '', '4', '0', '9', 'Blackmores澳佳宝 孕妇黄金素 180粒', 'Blackmores Pregnancy and Breastfeeding Gold 180 Capsules', 'BM黄金素', '皮肤急救法宝，强力补水', '', '新西兰原罐原装 官方正品 纯净A2蛋白质', '/uploads/images/20190729/63b7cacef160a2699898bda5c231b78f.jpg', null, '<p>11</p>\n<p><img class=\"img-ks-lazyload\" style=\"margin: 0px; padding: 0px; border: 0px; animation: 350ms linear 0ms 1 normal both running ks-fadeIn; opacity: 1; vertical-align: top; max-width: 100%; float: none; color: #404040; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\" src=\"https://img.alicdn.com/imgextra/i1/2555064063/O1CN01kq0zC31fstjynm847_!!2555064063.jpg\" alt=\"2段_05.jpg\" /><br style=\"margin: 0px; padding: 0px; color: #404040; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\" /><img class=\"img-ks-lazyload\" style=\"margin: 0px; padding: 0px; border: 0px; animation: 350ms linear 0ms 1 normal both running ks-fadeIn; opacity: 1; vertical-align: top; max-width: 100%; float: none; color: #404040; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\" src=\"https://img.alicdn.com/imgextra/i1/2555064063/O1CN0156ddqR1fstj1c0Wx8_!!2555064063.jpg\" alt=\"2段_06.jpg\" /></p>\n<p>2222</p>', '2020/10', '30', '1', '20.00', '30.00', '35.00', '30.00', '0.00', '0.50', '0.60', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564401969', '1565612731');
INSERT INTO `pm_goods` VALUES ('21', '7', '测试店铺', '1', '2', '0.00', '0', '19', '0-10-19-', '0', '', '4', '0', '9', 'Blackmores澳佳宝 叶酸片500mcg 90粒', 'Blackmores Folate 500mcg 90 Tablets', 'BM叶酸90粒', '孕期好伴侣', '', '', '/uploads/images/20190729/2808f8c49bc8ef2211cdeb8942968a61.jpg', null, '', '2020/10', '15', '1', '10.00', '15.00', '18.00', '2.00', '0.00', '0.20', '0.30', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564402099', '1564402099');
INSERT INTO `pm_goods` VALUES ('22', '7', '测试店铺', '1', '1', '0.00', '0', '20', '0-10-20-', '0', '', '15', '0', '12', 'Elevit 女士爱乐维 孕期维生素 100粒', 'Elevit Pregnancy Multivitamin Tablets 100 Pack （Export Only）', '爱乐维', '健康备孕 降低胎儿畸形', '', '', '/uploads/images/20190729/8225facb8669bff2ca9082f3404de33b.jpg', null, '', '2020/10', '50', '2', '30.00', '50.00', '60.00', '50.00', '0.00', '0.50', '0.30', '100', '999', '1', '1', '0', '0', '0', '1', '50', '1564402296', '1567178132');
INSERT INTO `pm_goods` VALUES ('23', '7', '测试店铺', '1', '1', '0.00', '0', '24', '0-4-24-', '0', '', '4', '0', '8', 'Swisse 高倍蜂胶2000mg 300粒', 'Swisse Ultiboost High Strength Propolis 2000mg 300 Capsules', 'SW蜂胶300粒', '液体黄金 澳洲蜂胶', '', '', '/uploads/images/20190803/504cb220ecb2a76a9794fa1ccb763b86.jpg', null, '', '2020/02/05', '25', '2', '20.00', '25.00', '30.00', '0.00', '0.00', '0.50', '0.60', '100', '999', '1', '1', '1', '0', '0', '1', '50', '1564829689', '1567178124');
INSERT INTO `pm_goods` VALUES ('24', '7', '测试店铺', '1', '2', '0.00', '0', '15', '0-1-15-', '0', '', '1', '1', '4', 'NK 7009 ugg 雪地靴 豆豆鞋 薰衣草紫', 'test', '鞋子', 'UUG', '', '', '/uploads/images/20190811/83c4de372c836bbeb4d378db0789faa9.jpg', null, '<p>222发斯蒂芬</p>\n<p>阿斯顿发斯蒂芬</p>\n<p><img src=\"/uploads/images/20190816/5415a5eeb090fdaa9ec32b458a705832.jpg\" alt=\"\" /></p>', '', '10', '4', '11.40', '15.00', '5.00', '12.00', '0.00', '0.50', '0.70', '0', '980', '1', '0', '1', '0', '0', '1', '50', '1565322339', '1566799065');
INSERT INTO `pm_goods` VALUES ('25', '7', '测试店铺', '1', '2', '0.00', '24', '15', '0-1-15-', '0', '', '1', '1', '4', '三件包邮优惠套餐', 'test', '鞋子', 'UUG', '', '', '/uploads/images/20190811/83c4de372c836bbeb4d378db0789faa9.jpg', null, '<p>222发斯蒂芬</p>\n<p>阿斯顿发斯蒂芬</p>\n<p><img src=\"/uploads/images/20190816/5415a5eeb090fdaa9ec32b458a705832.jpg\" alt=\"\" /></p>', '', '10', '3', '11.40', '100.00', '5.00', '12.00', '0.00', '0.50', '0.70', '0', '980', '3', '0', '1', '0', '0', '1', '50', '1565322339', '1566799065');
INSERT INTO `pm_goods` VALUES ('26', '7', '测试店铺', '1', '2', '0.00', '24', '15', '0-1-15-', '0', '', '1', '1', '4', '六件包邮优惠套餐', 'test', '鞋子', 'UUG', '', '', '/uploads/images/20190811/83c4de372c836bbeb4d378db0789faa9.jpg', null, '<p>222发斯蒂芬</p>\n<p>阿斯顿发斯蒂芬</p>\n<p><img src=\"/uploads/images/20190816/5415a5eeb090fdaa9ec32b458a705832.jpg\" alt=\"\" /></p>', '', '10', '1', '11.40', '180.00', '5.00', '12.00', '0.00', '0.50', '0.70', '0', '980', '6', '0', '1', '0', '0', '1', '50', '1565322339', '1566799065');

-- ----------------------------
-- Table structure for `pm_goods_cate`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods_cate`;
CREATE TABLE `pm_goods_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '9999',
  `path` varchar(200) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `num` int(11) NOT NULL,
  `comm` tinyint(4) NOT NULL,
  `comm1` tinyint(4) NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_cate
-- ----------------------------
INSERT INTO `pm_goods_cate` VALUES ('1', '0', '奶粉专区', '', '20', '0-1-', '/uploads/images/20190729/b96302dd748bd16420f18181664fd85d.jpg', '', '0', '1', '0', '', '', '1561185748', '1564400258');
INSERT INTO `pm_goods_cate` VALUES ('4', '0', '营养保健', '', '50', '0-4-', '/uploads/images/20190729/4c6aecdd4dcb5eeb7f146e7f53942c4d.jpg', '', '0', '1', '0', '', '', '1564111190', '1564398347');
INSERT INTO `pm_goods_cate` VALUES ('5', '0', '纤体瘦身', '', '50', '0-5-', '/uploads/images/20190729/61d16a9a3734d0ee4accbcd5059e0a2c.jpg', '', '0', '1', '0', '', '', '1564111296', '1564398354');
INSERT INTO `pm_goods_cate` VALUES ('7', '0', '美妆护肤', '', '50', '0-7-', '/uploads/images/20190729/a13c35609ae92b6ccd5730e7b0f47348.jpg', '', '0', '1', '0', '', '', '1564112889', '1564398361');
INSERT INTO `pm_goods_cate` VALUES ('8', '0', '购物须知', '', '999', '0-8-', '/uploads/images/20190726/bf3a4c1de31f4da3ee8d709591637397.jpg', '', '0', '1', '0', '', '', '1564112901', '1564400216');
INSERT INTO `pm_goods_cate` VALUES ('13', '0', '围巾鞋子', '', '50', '0-13-', '/uploads/images/20190729/01bba9d4c089764f30fc43442cf6b761.jpg', '', '0', '1', '0', '', '', '1564387478', '1564398340');
INSERT INTO `pm_goods_cate` VALUES ('9', '0', '婴幼儿区', '', '50', '0-9-', '/uploads/images/20190729/34b07f95d337a98b359b2c08a490de2c.jpg', '', '0', '1', '0', '', '', '1564370609', '1564398367');
INSERT INTO `pm_goods_cate` VALUES ('10', '0', '孕妈专区', '', '50', '0-10-', '/uploads/images/20190729/9edbb30978db4002909f71c1d02e082a.jpg', '', '0', '1', '0', '', '', '1564370628', '1564398319');
INSERT INTO `pm_goods_cate` VALUES ('11', '0', '天然蜂蜜', '', '50', '0-11-', '/uploads/images/20190729/b1bc5976dd2cd196063045398168df2f.jpg', '', '0', '1', '0', '', '', '1564370646', '1564398326');
INSERT INTO `pm_goods_cate` VALUES ('12', '0', '美味零食', '', '50', '0-12-', '/uploads/images/20190729/e6235c84686995d02e222cb12bf0d61f.jpg', '', '0', '1', '0', '', '', '1564370660', '1564398332');
INSERT INTO `pm_goods_cate` VALUES ('14', '0', '洗护日用', '', '50', '0-14-', '', '', '0', '0', '0', '', '', '1564390647', '1564390647');
INSERT INTO `pm_goods_cate` VALUES ('15', '1', 'A2', '', '50', '0-1-15-', '', '', '0', '0', '1', '', '', '1564394407', '1564398393');
INSERT INTO `pm_goods_cate` VALUES ('16', '1', '爱他美', '', '50', '0-1-16-', '', '', '0', '0', '1', '', '', '1564394504', '1564398402');
INSERT INTO `pm_goods_cate` VALUES ('17', '1', '贝拉米', '', '50', '0-1-17-', '', '', '0', '0', '0', '', '', '1564394515', '1564398411');
INSERT INTO `pm_goods_cate` VALUES ('37', '0', '网红药品', '', '50', '0-37-', '/uploads/images/20190729/6edf57b15a72ec9a6555c1718f9c21c6.jpg', '', '0', '1', '0', '', '', '1564399897', '1564400180');
INSERT INTO `pm_goods_cate` VALUES ('18', '10', '孕妈奶粉', '', '50', '0-10-18-', '', '', '0', '0', '0', '', '', '1564394559', '1564394691');
INSERT INTO `pm_goods_cate` VALUES ('19', '10', '孕妈保健', '', '50', '0-10-19-', '', '', '0', '0', '1', '', '', '1564394584', '1564401049');
INSERT INTO `pm_goods_cate` VALUES ('20', '10', '孕妈必备', '', '50', '0-10-20-', '', '', '0', '0', '0', '', '', '1564394652', '1564394652');
INSERT INTO `pm_goods_cate` VALUES ('21', '4', '护肝片', '', '50', '0-4-21-', '', '', '0', '0', '0', '', '', '1564394814', '1564394814');
INSERT INTO `pm_goods_cate` VALUES ('22', '4', '维生素', '', '50', '0-4-22-', '', '', '0', '0', '1', '', '', '1564394830', '1564401088');
INSERT INTO `pm_goods_cate` VALUES ('23', '4', '鱼油', '', '50', '0-4-23-', '', '', '0', '0', '0', '', '', '1564394842', '1564394842');
INSERT INTO `pm_goods_cate` VALUES ('24', '4', '蜂胶', '', '50', '0-4-24-', '', '', '0', '0', '0', '', '', '1564394853', '1564394853');
INSERT INTO `pm_goods_cate` VALUES ('25', '4', '卵磷脂', '', '50', '0-4-25-', '', '', '0', '0', '0', '', '', '1564394869', '1564394869');
INSERT INTO `pm_goods_cate` VALUES ('26', '4', '维骨力', '', '50', '0-4-26-', '', '', '0', '0', '0', '', '', '1564394881', '1564394881');
INSERT INTO `pm_goods_cate` VALUES ('27', '4', '蛋白粉', '', '50', '0-4-27-', '', '', '0', '0', '0', '', '', '1564394892', '1564394892');
INSERT INTO `pm_goods_cate` VALUES ('28', '4', '益生菌', '', '50', '0-4-28-', '', '', '0', '0', '0', '', '', '1564394902', '1564394902');
INSERT INTO `pm_goods_cate` VALUES ('29', '4', '其他', '', '50', '0-4-29-', '', '', '0', '0', '0', '', '', '1564394911', '1564394911');
INSERT INTO `pm_goods_cate` VALUES ('30', '14', '牙刷', '', '50', '0-14-30-', '', '', '0', '0', '0', '', '', '1564394932', '1564394932');
INSERT INTO `pm_goods_cate` VALUES ('31', '14', '电动牙刷', '', '50', '0-14-31-', '', '', '0', '0', '0', '', '', '1564394946', '1564394946');
INSERT INTO `pm_goods_cate` VALUES ('32', '14', '木瓜膏', '', '50', '0-14-32-', '', '', '0', '0', '0', '', '', '1564394960', '1564394960');
INSERT INTO `pm_goods_cate` VALUES ('33', '14', '驱蚊必备', '', '50', '0-14-33-', '', '', '0', '0', '0', '', '', '1564394970', '1564394970');
INSERT INTO `pm_goods_cate` VALUES ('34', '14', '网红杯', '', '50', '0-14-34-', '', '', '0', '0', '0', '', '', '1564394992', '1564394992');
INSERT INTO `pm_goods_cate` VALUES ('35', '14', '洗洁精', '', '50', '0-14-35-', '', '', '0', '0', '0', '', '', '1564395005', '1564395005');
INSERT INTO `pm_goods_cate` VALUES ('36', '14', '其他日用品', '', '50', '0-14-36-', '', '', '0', '0', '0', '', '', '1564395019', '1564395019');

-- ----------------------------
-- Table structure for `pm_goods_model`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods_model`;
CREATE TABLE `pm_goods_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_model
-- ----------------------------
INSERT INTO `pm_goods_model` VALUES ('1', '鞋子', '1562989209', '1562989209');

-- ----------------------------
-- Table structure for `pm_goods_push`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods_push`;
CREATE TABLE `pm_goods_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cateID` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `goodsID` int(11) NOT NULL,
  `goodsName` varchar(200) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_push
-- ----------------------------
INSERT INTO `pm_goods_push` VALUES ('9', '3', '0', '10', 'A2铂金 二段 A2 Follow On Formula', '1564396930');
INSERT INTO `pm_goods_push` VALUES ('8', '2', '0', '10', 'A2铂金 二段 A2 Follow On Formula', '1564396623');
INSERT INTO `pm_goods_push` VALUES ('6', '1', '0', '9', 'A2铂金 一段 A2 Infant Formula ', '1564396423');
INSERT INTO `pm_goods_push` VALUES ('7', '1', '0', '7', 'A2铂金 三段 A2 Premium Toddler', '1564396423');
INSERT INTO `pm_goods_push` VALUES ('10', '3', '0', '17', '爱他美白金 二段 Aptamil Profutura Follow On Formula', '1564401634');
INSERT INTO `pm_goods_push` VALUES ('11', '1', '0', '22', 'Elevit 女士爱乐维 孕期维生素 100粒', '1564402320');
INSERT INTO `pm_goods_push` VALUES ('12', '3', '4', '23', 'Swisse 高倍蜂胶2000mg 300粒', '1564829737');

-- ----------------------------
-- Table structure for `pm_goods_spec_price`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods_spec_price`;
CREATE TABLE `pm_goods_spec_price` (
  `item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格商品id',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品id',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '规格键名',
  `key_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '规格键名中文',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `store_count` int(11) unsigned DEFAULT '10' COMMENT '库存数量',
  `bar_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '商品条形码',
  `weight` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '重量',
  `isBaoyou` tinyint(4) DEFAULT NULL COMMENT '0不包邮 1包邮',
  `spec_img` varchar(255) DEFAULT NULL COMMENT '规格商品主图',
  `prom_id` int(10) DEFAULT '0' COMMENT '活动id',
  `prom_type` tinyint(2) DEFAULT '0' COMMENT '参加活动类型',
  PRIMARY KEY (`item_id`),
  KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_spec_price
-- ----------------------------
INSERT INTO `pm_goods_spec_price` VALUES ('13', '24', '2_5', '尺码:40码 颜色:红色', '15.00', '0', '', '0.5', null, '', '0', '0');
INSERT INTO `pm_goods_spec_price` VALUES ('14', '24', '2_6', '尺码:40码 颜色:蓝色', '15.00', '0', '', '0.5', null, '', '0', '0');
INSERT INTO `pm_goods_spec_price` VALUES ('15', '24', '3_5', '尺码:42码 颜色:红色', '15.00', '0', '', '0.5', null, '', '0', '0');
INSERT INTO `pm_goods_spec_price` VALUES ('16', '24', '3_6', '尺码:42码 颜色:蓝色', '15.00', '0', '', '0.5', null, '', '0', '0');

-- ----------------------------
-- Table structure for `pm_link`
-- ----------------------------
DROP TABLE IF EXISTS `pm_link`;
CREATE TABLE `pm_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picname` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_link
-- ----------------------------
INSERT INTO `pm_link` VALUES ('5', '8', '0-8-', '百度', '/uploads/images/20190427/e57487e412ec8e2c85699a82af1d4907.jpg', 'http://www.baidu.com', '50', '1556365666', '1556366588');

-- ----------------------------
-- Table structure for `pm_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `pm_login_log`;
CREATE TABLE `pm_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL COMMENT '1手机 2微信',
  `memberID` int(11) NOT NULL,
  `account` varchar(100) NOT NULL,
  `loginTime` int(11) NOT NULL,
  `loginIP` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_login_log
-- ----------------------------
INSERT INTO `pm_login_log` VALUES ('1', '0', '1', '13500000000', '1557043064', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('2', '0', '5', '13500000002', '1557123132', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('3', '0', '5', '13500000002', '1557200159', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('4', '0', '5', '13500000002', '1557200801', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('5', '0', '5', '13500000002', '1557200994', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('6', '0', '5', '13500000002', '1557208325', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('7', '0', '5', '13500000002', '1557214386', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('8', '0', '8', '13510000003', '1557902554', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('9', '0', '5', '13500000002', '1558054528', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('10', '0', '5', '13500000002', '1558065129', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('11', '0', '1', '13500000000', '1558171198', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('12', '0', '1', '13500000000', '1558400932', '61.158.152.187');
INSERT INTO `pm_login_log` VALUES ('13', '0', '1', '13500000000', '1558402110', '61.158.152.187');
INSERT INTO `pm_login_log` VALUES ('14', '0', '1', '13500000000', '1558416536', '123.55.156.176');
INSERT INTO `pm_login_log` VALUES ('15', '0', '1', '13500000000', '1558662552', '117.158.110.78');
INSERT INTO `pm_login_log` VALUES ('16', '0', '1', '13500000000', '1560243253', '223.104.108.12');
INSERT INTO `pm_login_log` VALUES ('17', '0', '1', '1212121212', '1563767536', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('18', '0', '1', '1212121212', '1563767569', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('19', '0', '1', '1212121212', '1563767606', '127.0.0.1');
INSERT INTO `pm_login_log` VALUES ('20', '0', '10002', 'ob5wP1Phg9aYeeW_Q162FyDJ-LaA', '1566188461', '127.0.0.1');

-- ----------------------------
-- Table structure for `pm_member`
-- ----------------------------
DROP TABLE IF EXISTS `pm_member`;
CREATE TABLE `pm_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `nickname` varchar(100) NOT NULL,
  `tjID` int(11) NOT NULL,
  `tjName` varchar(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `sn` varchar(50) NOT NULL,
  `wechat` varchar(50) NOT NULL,
  `headimg` varchar(200) NOT NULL,
  `team` tinyint(4) NOT NULL,
  `disable` tinyint(4) NOT NULL COMMENT '0正常 1禁用',
  `token` varchar(200) NOT NULL,
  `token_out` int(11) NOT NULL,
  `createTime` int(11) NOT NULL COMMENT '注册时间',
  `createIP` varchar(20) NOT NULL COMMENT '注册IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_member
-- ----------------------------
INSERT INTO `pm_member` VALUES ('10001', 'dsfsdfsdfsdfsdf', '', '', '张小黑', '10002', '月明', '张黑', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/PLh3YV0ZQhVw7n3D5kflfctMmErkic2CHHDEzTa36vuCLVCNNqTYgJCB4OxZrgz1Gqy4odIc97iblFFlF7u9DcIg/132', '0', '0', '7f92012aaa7c2d71d3415968311effaa0c923e45', '1570152556', '1563767631', '127.0.0.1');
INSERT INTO `pm_member` VALUES ('10002', 'ob5wP1Phg9aYeeW_Q162FyDJ-LaA', '13500000001', '', '月明', '0', '', '张三', '', '3131313', 'http://thirdwx.qlogo.cn/mmopen/vi_32/zK1Fs3gpSSte4nOJlEepugE5HXA6t1rqs231iczJywgzVNlYh73CJQiaFlz6OoIBQgU9BxgsEjJn92FCrDNGZaEQ/132', '1', '0', 'b9a6fdf376af956a870d74e0a82e848603bf6779', '1570172489', '1566188328', '127.0.0.1');

-- ----------------------------
-- Table structure for `pm_member_code`
-- ----------------------------
DROP TABLE IF EXISTS `pm_member_code`;
CREATE TABLE `pm_member_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `regcode` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_member_code
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_message`
-- ----------------------------
DROP TABLE IF EXISTS `pm_message`;
CREATE TABLE `pm_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(300) NOT NULL,
  `read` tinyint(4) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_message
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_model_spec`
-- ----------------------------
DROP TABLE IF EXISTS `pm_model_spec`;
CREATE TABLE `pm_model_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格表',
  `mID` int(11) DEFAULT '0' COMMENT '规格类型',
  `name` varchar(55) DEFAULT NULL COMMENT '规格名称',
  `sort` int(11) DEFAULT '50' COMMENT '排序',
  `values` varchar(500) DEFAULT NULL,
  `search_index` tinyint(1) DEFAULT '1' COMMENT '是否需要检索：1是，0否',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_model_spec
-- ----------------------------
INSERT INTO `pm_model_spec` VALUES ('1', '1', '颜色', '50', '红色\n蓝色\n白色', '1', '0', '0');
INSERT INTO `pm_model_spec` VALUES ('2', '1', '尺码', '50', '35码\n40码\n42码\n43码', '1', '0', '0');

-- ----------------------------
-- Table structure for `pm_model_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `pm_model_spec_item`;
CREATE TABLE `pm_model_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格项id',
  `specID` int(11) DEFAULT NULL COMMENT '规格id',
  `item` varchar(54) DEFAULT NULL COMMENT '规格项',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_model_spec_item
-- ----------------------------
INSERT INTO `pm_model_spec_item` VALUES ('1', '2', '35码');
INSERT INTO `pm_model_spec_item` VALUES ('2', '2', '40码');
INSERT INTO `pm_model_spec_item` VALUES ('3', '2', '42码');
INSERT INTO `pm_model_spec_item` VALUES ('4', '2', '43码');
INSERT INTO `pm_model_spec_item` VALUES ('5', '1', '红色');
INSERT INTO `pm_model_spec_item` VALUES ('6', '1', '蓝色');
INSERT INTO `pm_model_spec_item` VALUES ('7', '1', '白色');

-- ----------------------------
-- Table structure for `pm_node`
-- ----------------------------
DROP TABLE IF EXISTS `pm_node`;
CREATE TABLE `pm_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '节点名称',
  `value` varchar(50) NOT NULL COMMENT '菜单名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否激活 1：是 2：否',
  `remark` varchar(100) NOT NULL COMMENT '备注说明',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '节点等级',
  `icon` varchar(20) NOT NULL COMMENT '图标',
  `default` tinyint(4) NOT NULL COMMENT '附加参数',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '菜单显示类型 0:不显示 1:导航菜单 2:左侧菜单',
  PRIMARY KEY (`id`),
  KEY `level` (`level`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_node
-- ----------------------------
INSERT INTO `pm_node` VALUES ('1', '主页', '', '1', '', '0', '1', 'layui-icon-home', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('2', '内容管理', '', '1', '', '0', '1', 'layui-icon-app', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('3', '控制台', 'index', '1', '', '1', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('4', '分类管理', 'category', '1', '', '2', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('5', '文章管理', 'article', '1', '', '2', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('6', '设置', '', '1', '', '0', '1', 'layui-icon-set', '0', '100', '1');
INSERT INTO `pm_node` VALUES ('7', '应用设置', 'setting', '1', '', '6', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('8', '节点管理', 'node', '1', '', '6', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('9', '查看信息', 'setting/index', '1', '', '7', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('10', '查看', 'node/index', '1', '', '8', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('11', '发布', 'node/pub', '1', '', '8', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('12', '删除', 'node/del', '1', '', '8', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('13', '控制台', 'index/console', '1', '', '3', '3', '', '1', '50', '0');
INSERT INTO `pm_node` VALUES ('14', '用户', '', '1', '', '0', '1', 'layui-icon-user', '0', '90', '1');
INSERT INTO `pm_node` VALUES ('15', '后台管理员', 'user', '1', '', '14', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('16', '用户组管理', 'role', '1', '', '14', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('17', '单页面管理', 'onepage', '1', '', '2', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('18', '广告管理', 'ad', '1', '', '2', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('19', '友情链接', 'link', '1', '', '2', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('20', '文章列表', 'article/index', '1', '', '5', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('21', '回收站', 'article/trash', '1', '', '5', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('22', '文章列表', 'article/index', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('23', '发布', 'article/pub', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('24', '删除', 'article/del', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('25', '清除回收站', 'article/truedel', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('26', '还原', 'article/restore', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('27', '移动', 'article/move', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('28', '更改状态', 'article/status', '1', '', '5', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('29', '列表', 'category/index', '1', '', '4', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('30', '发布', 'category/pub', '1', '', '4', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('31', '删除', 'category/del', '1', '', '4', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('32', '列表', 'onepage/index', '1', '', '17', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('33', '发布', 'onepage/pub', '1', '', '17', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('34', '删除', 'onepage/del', '1', '', '17', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('35', '列表', 'ad/index', '1', '', '18', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('36', '发布', 'ad/pub', '1', '', '18', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('37', '删除', 'ad/del', '1', '', '18', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('38', '列表', 'link/index', '1', '', '19', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('39', '发布', 'link/pub', '1', '', '19', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('40', '删除', 'link/del', '1', '', '19', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('41', '成员列表', 'user/index', '1', '', '15', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('42', '登录日志', 'user/log', '1', '', '15', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('43', '发布', 'user/pub', '1', '', '15', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('44', '删除', 'user/del', '1', '', '15', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('45', '删除日志', 'user/delog', '1', '', '15', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('46', '列表', 'role/index', '1', '', '16', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('47', '发布', 'role/pub', '1', '', '16', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('48', '删除', 'role/del', '1', '', '16', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('49', '权限设置', 'role/access', '1', '', '16', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('50', '清除缓存', 'index/clearcache', '1', '', '3', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('51', '会员管理', '', '1', '', '0', '1', 'layui-icon-username', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('52', '会员列表', 'member', '1', '', '51', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('53', '列表', 'member/index', '1', '', '52', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('54', '发布', 'member/pub', '1', '', '52', '3', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('84', '会员充值', 'chongzhi', '1', '', '73', '2', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('87', '待发货', 'order/peing', '1', '', '83', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('85', '全部', 'order/index', '1', '', '83', '3', '', '0', '0', '1');
INSERT INTO `pm_node` VALUES ('82', '选项设置', 'option', '1', '', '6', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('65', '商品管理', '', '1', '', '0', '1', 'layui-icon-cart', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('66', '商品列表', 'goods', '1', '', '65', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('83', '订单管理', 'order', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('69', '推送列表', 'goodsPush', '1', '', '65', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('70', '商品分类', 'GoodsCate', '1', '', '65', '2', '', '0', '30', '1');
INSERT INTO `pm_node` VALUES ('72', '促销活动', '', '1', '', '0', '1', 'layui-icon-star', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('73', '财务管理', '', '1', '', '0', '1', 'layui-icon-dollar', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('74', '优惠券', 'coupon', '1', '', '72', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('75', '今日抢购', 'flash', '1', '', '72', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('77', '商品模型', 'goodsModel', '1', '', '65', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('78', '模型列表', 'goodsModel/index', '1', '', '77', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('79', '参数规格', 'goodsModel/spec', '1', '', '77', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('80', '品牌管理', 'brand', '1', '', '65', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('88', '已发货', 'order/fahuo', '1', '', '83', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('89', '交易关闭', 'order/close', '1', '', '83', '3', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('90', '包裹定位', 'baoguo', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('91', '基金返利', 'fund', '1', '', '73', '2', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('92', '财务明细', 'finance', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('93', '待支付', 'order/nopay', '1', '', '83', '3', '', '0', '10', '1');
INSERT INTO `pm_node` VALUES ('94', '商家管理', '', '1', '', '0', '1', 'layui-icon-group', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('95', '商家列表', 'shop', '1', '', '94', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('96', '城市管理', 'city', '1', '', '94', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('97', '快递管理', 'express', '1', '', '94', '2', '', '0', '50', '1');

-- ----------------------------
-- Table structure for `pm_onepage`
-- ----------------------------
DROP TABLE IF EXISTS `pm_onepage`;
CREATE TABLE `pm_onepage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `description` varchar(500) NOT NULL,
  `keyword` varchar(500) NOT NULL,
  `updateTime` int(10) NOT NULL,
  `createTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_onepage
-- ----------------------------
INSERT INTO `pm_onepage` VALUES ('1', '关于我们', '<p>123123123</p>\n<p>发送到发地方阿斯蒂芬</p>\n<p>阿斯蒂芬阿斯蒂芬阿萨德</p>', '', '', '1566285023', '1566284260');
INSERT INTO `pm_onepage` VALUES ('2', '客服', '<p>客服111</p>\n<p>222</p>', '', '', '1567440287', '1567440287');

-- ----------------------------
-- Table structure for `pm_option_cate`
-- ----------------------------
DROP TABLE IF EXISTS `pm_option_cate`;
CREATE TABLE `pm_option_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(20) NOT NULL,
  `sort` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_option_cate
-- ----------------------------
INSERT INTO `pm_option_cate` VALUES ('1', '商品推送', '', '50', '1563769874', '1563769874');
INSERT INTO `pm_option_cate` VALUES ('2', '产品功效', '', '50', '1563897789', '1563897789');
INSERT INTO `pm_option_cate` VALUES ('3', '商品标签', '', '50', '1563898269', '1563898269');
INSERT INTO `pm_option_cate` VALUES ('4', '品牌分类', '', '50', '1564556522', '1564556522');

-- ----------------------------
-- Table structure for `pm_option_item`
-- ----------------------------
DROP TABLE IF EXISTS `pm_option_item`;
CREATE TABLE `pm_option_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `value` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `pinyin` varchar(4) NOT NULL,
  `ext` varchar(100) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_option_item
-- ----------------------------
INSERT INTO `pm_option_item` VALUES ('1', '1', '每日精品', '', '3', '50', 'M', '大家都在买', '1563769911', '1564155971');
INSERT INTO `pm_option_item` VALUES ('2', '1', '国内现货', '', '2', '50', 'G', '大家都在囤', '1563770010', '1564155988');
INSERT INTO `pm_option_item` VALUES ('3', '1', '特惠推荐', '', '1', '50', 'T', '大家都在买', '1563770054', '1564155992');
INSERT INTO `pm_option_item` VALUES ('4', '2', '美白', '', '', '50', 'M', '', '1563898010', '1563898010');
INSERT INTO `pm_option_item` VALUES ('5', '2', '降压', '', '', '50', 'J', '', '1563898053', '1563898053');
INSERT INTO `pm_option_item` VALUES ('6', '2', '改善贫血', '', '', '50', 'G', '', '1563898075', '1563898075');
INSERT INTO `pm_option_item` VALUES ('7', '4', '美容彩妆', '', '', '50', 'M', '', '1564556543', '1564556543');
INSERT INTO `pm_option_item` VALUES ('8', '4', '个人洗护', '', '', '50', 'G', '', '1564556560', '1564556560');
INSERT INTO `pm_option_item` VALUES ('9', '4', '宝宝奶粉', '', '', '50', 'B', '', '1564992927', '1564992927');
INSERT INTO `pm_option_item` VALUES ('10', '4', '婴儿必备', '', '', '50', 'Y', '', '1564992968', '1564992968');
INSERT INTO `pm_option_item` VALUES ('11', '4', '孕妈必备', '', '', '50', 'Y', '', '1564992979', '1564992979');
INSERT INTO `pm_option_item` VALUES ('12', '4', '天然保健', '', '', '50', 'T', '', '1564992987', '1564992987');
INSERT INTO `pm_option_item` VALUES ('13', '4', '网红药品', '', '', '50', 'W', '', '1564993009', '1564993009');
INSERT INTO `pm_option_item` VALUES ('14', '4', '纯净蜂蜜', '', '', '50', 'C', '', '1564993023', '1564993023');
INSERT INTO `pm_option_item` VALUES ('15', '4', '围巾&Ugg', '', '', '50', 'W', '', '1564993055', '1564993055');
INSERT INTO `pm_option_item` VALUES ('16', '4', '成人奶粉', '', '', '50', 'C', '', '1564993378', '1564993378');
INSERT INTO `pm_option_item` VALUES ('17', '4', '美味零食', '', '', '50', 'M', '', '1564993595', '1564993595');
INSERT INTO `pm_option_item` VALUES ('18', '4', '居家日用', '', '', '50', 'J', '', '1564993603', '1564993603');

-- ----------------------------
-- Table structure for `pm_order`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order`;
CREATE TABLE `pm_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `couponID` int(11) NOT NULL COMMENT '优惠券ID',
  `order_no` varchar(50) NOT NULL,
  `total` decimal(8,2) NOT NULL COMMENT '订单总金额(商品金额+快递费-优惠金额)',
  `point` int(11) NOT NULL COMMENT '获取积分',
  `bonus` int(8) NOT NULL COMMENT '奖金',
  `goodsMoney` decimal(8,2) NOT NULL COMMENT '商品总金额',
  `discount` varchar(10) DEFAULT NULL COMMENT '优惠券金额',
  `money` decimal(8,2) NOT NULL COMMENT '在线支付金额',
  `wallet` decimal(8,2) NOT NULL COMMENT '余额支付金额',
  `inprice` decimal(8,2) NOT NULL COMMENT '总成本',
  `payment` decimal(8,2) NOT NULL COMMENT '运费总金额',
  `addressID` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `sn` varchar(30) DEFAULT NULL,
  `front` varchar(200) DEFAULT NULL,
  `back` varchar(200) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `addressDetail` varchar(100) DEFAULT NULL,
  `sender` varchar(20) DEFAULT NULL,
  `senderTel` varchar(30) DEFAULT NULL,
  `intr` varchar(300) DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `print` tinyint(4) NOT NULL COMMENT '0未打印 1已打印',
  `payType` tinyint(11) NOT NULL COMMENT '1omi支付 2余额支付',
  `payStatus` tinyint(11) NOT NULL COMMENT '0未支付 1已支付',
  `status` tinyint(4) NOT NULL COMMENT '0待支付 1待发货 2已发货(待收货) 3待评价 99交易关闭',
  `hide` tinyint(4) NOT NULL,
  `cancel` tinyint(4) NOT NULL COMMENT '取消订单',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order
-- ----------------------------
INSERT INTO `pm_order` VALUES ('20', '10002', '16', '72863769', '75.00', '0', '75', '80.00', '5.00', '0.00', '0.00', '65.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '', null, '0', '0', '0', '3', '1', '0', '1566291433', '1566291433');
INSERT INTO `pm_order` VALUES ('22', '10002', '0', '19082120265963', '180.00', '10', '180', '180.00', '0', '0.00', '0.00', '60.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '', null, '0', '1', '1', '1', '0', '0', '1566390419', '1566390419');
INSERT INTO `pm_order` VALUES ('32', '10002', '0', '19090400532989', '159.80', '155', '160', '155.00', '0', '0.00', '0.00', '110.00', '4.80', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', null, null, '0', '0', '0', '0', '0', '0', '1567529609', '0');
INSERT INTO `pm_order` VALUES ('33', '10002', '0', '19090400585719', '54.20', '50', '54', '50.00', '0', '0.00', '0.00', '30.00', '4.20', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', null, null, '0', '0', '0', '0', '0', '0', '1567529937', '0');
INSERT INTO `pm_order` VALUES ('65', '10002', '19', '19090411430821', '73.60', '75', '4', '70.00', '3.00', '0.00', '0.00', '50.00', '6.60', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '不差钱', null, '0', '0', '0', '0', '0', '0', '1567568588', '0');
INSERT INTO `pm_order` VALUES ('66', '10002', '16', '19090411430843', '55.00', '60', '4', '60.00', '5.00', '0.00', '0.00', '50.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '千万别搞错了', null, '0', '0', '0', '0', '0', '0', '1567568588', '0');
INSERT INTO `pm_order` VALUES ('67', '10002', '0', '19090414074022', '54.20', '50', '2', '50.00', '0', '0.00', '0.00', '30.00', '4.20', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '1567577260', '0');
INSERT INTO `pm_order` VALUES ('68', '10002', '0', '19090414103589', '54.20', '50', '2', '50.00', '0', '0.00', '0.00', '30.00', '4.20', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '1567577435', '0');
INSERT INTO `pm_order` VALUES ('69', '10002', '0', '19090414103538', '60.00', '60', '2', '60.00', '0', '0.00', '0.00', '50.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '1567577435', '0');
INSERT INTO `pm_order` VALUES ('70', '10002', '0', '19090414103535', '1.00', '0', '0', '0.00', '0', '0.00', '0.00', '0.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '1567577435', '0');
INSERT INTO `pm_order` VALUES ('71', '10002', '0', '19090414103561', '60.00', '60', '2', '60.00', '0', '0.00', '0.00', '50.00', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '1567577435', '0');

-- ----------------------------
-- Table structure for `pm_order_baoguo`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order_baoguo`;
CREATE TABLE `pm_order_baoguo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) NOT NULL COMMENT '用户订单号',
  `memberID` int(11) NOT NULL,
  `order_no` varchar(50) NOT NULL COMMENT '商家订单号',
  `type` tinyint(4) NOT NULL COMMENT '1，2奶粉类 7鞋子，剩余其他类',
  `payment` decimal(8,2) NOT NULL COMMENT '物流费',
  `wuliuInprice` decimal(8,2) NOT NULL,
  `weight` decimal(8,2) NOT NULL COMMENT '总价格',
  `kuaidi` varchar(50) NOT NULL COMMENT '物流公司',
  `kdNo` varchar(500) NOT NULL COMMENT '物流号',
  `eimg` varchar(1000) NOT NULL,
  `image` text NOT NULL,
  `name` varchar(20) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `county` varchar(50) NOT NULL,
  `addressDetail` varchar(200) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `senderTel` varchar(50) NOT NULL,
  `status` tinyint(1) unsigned zerofill NOT NULL COMMENT '0未支付 1已支付',
  `snStatus` tinyint(4) NOT NULL COMMENT '身份证0未上传，1已上传',
  `flag` tinyint(4) NOT NULL COMMENT '0未导出 1已导出',
  `print` tinyint(4) NOT NULL DEFAULT '0',
  `cancel` tinyint(4) NOT NULL COMMENT '取消订单',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_baoguo
-- ----------------------------
INSERT INTO `pm_order_baoguo` VALUES ('9', '20', '10002', '72863769', '1', '0.00', '3.85', '1.10', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1566291433', '0');
INSERT INTO `pm_order_baoguo` VALUES ('10', '20', '10002', '72863769', '4', '0.00', '6.72', '1.20', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1566291433', '0');
INSERT INTO `pm_order_baoguo` VALUES ('12', '22', '10002', '19082120265963', '1', '0.00', '7.35', '2.10', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '1', '0', '0', '0', '0', '1566390419', '0');
INSERT INTO `pm_order_baoguo` VALUES ('13', '22', '10002', '19082120265963', '1', '0.00', '7.35', '2.10', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '1', '0', '0', '0', '0', '1566390419', '0');
INSERT INTO `pm_order_baoguo` VALUES ('22', '32', '10002', '19090400532989', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567529609', '0');
INSERT INTO `pm_order_baoguo` VALUES ('23', '32', '10002', '19090400532989', '1', '0.00', '7.70', '2.20', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567529609', '0');
INSERT INTO `pm_order_baoguo` VALUES ('24', '32', '10002', '19090400532989', '4', '0.60', '5.04', '0.90', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567529609', '0');
INSERT INTO `pm_order_baoguo` VALUES ('25', '33', '10002', '19090400585719', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567529937', '0');
INSERT INTO `pm_order_baoguo` VALUES ('26', '38', '10002', '19090410480631', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567565286', '0');
INSERT INTO `pm_order_baoguo` VALUES ('30', '65', '10002', '19090411430821', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567568588', '0');
INSERT INTO `pm_order_baoguo` VALUES ('31', '65', '10002', '19090411430821', '4', '2.40', '3.36', '0.60', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567568588', '0');
INSERT INTO `pm_order_baoguo` VALUES ('32', '66', '10002', '19090411430843', '1', '0.00', '7.70', '2.20', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567568588', '0');
INSERT INTO `pm_order_baoguo` VALUES ('33', '67', '10002', '19090414074022', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567577260', '0');
INSERT INTO `pm_order_baoguo` VALUES ('34', '68', '10002', '19090414103589', '15', '4.20', '1.68', '0.30', '中环($6/kg)', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567577435', '0');
INSERT INTO `pm_order_baoguo` VALUES ('35', '69', '10002', '19090414103538', '1', '0.00', '7.70', '2.20', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567577435', '0');
INSERT INTO `pm_order_baoguo` VALUES ('36', '71', '10002', '19090414103561', '1', '0.00', '7.70', '2.20', '澳邮', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '张三', '13500000000', '0', '0', '0', '0', '0', '1567577435', '0');

-- ----------------------------
-- Table structure for `pm_order_cart`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order_cart`;
CREATE TABLE `pm_order_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `goodsID` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `specID` int(11) NOT NULL COMMENT '商品规格',
  `name` varchar(200) NOT NULL,
  `picname` varchar(200) NOT NULL,
  `spec` varchar(200) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `number` int(11) NOT NULL,
  `trueNumber` int(11) NOT NULL COMMENT '真实商品数量比如2个3件的套餐就显示6',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_cart
-- ----------------------------
INSERT INTO `pm_order_cart` VALUES ('14', '10002', '20', '10', '0', '0', 'A2铂金 二段 A2 Follow On Formula', '/uploads/images/20190729/e47cfa2678fc5785d957bb71e4e19f6e.jpg', '', '29.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('15', '10002', '20', '23', '0', '0', 'Swisse 高倍蜂胶2000mg 300粒', '/uploads/images/20190803/504cb220ecb2a76a9794fa1ccb763b86.jpg', '', '23.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('16', '10002', '20', '20', '0', '0', 'Blackmores澳佳宝 孕妇黄金素 180粒', '/uploads/images/20190729/63b7cacef160a2699898bda5c231b78f.jpg', '', '28.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('18', '10002', '22', '26', '24', '0', '六件包邮优惠套餐', '/uploads/images/20190811/83c4de372c836bbeb4d378db0789faa9.jpg', '', '180.00', '1', '6');
INSERT INTO `pm_order_cart` VALUES ('28', '10002', '32', '22', '0', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '/uploads/images/20190729/8225facb8669bff2ca9082f3404de33b.jpg', '', '50.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('29', '10002', '32', '10', '0', '0', 'A2铂金 二段 A2 Follow On Formula', '/uploads/images/20190729/e47cfa2678fc5785d957bb71e4e19f6e.jpg', '', '29.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('30', '10002', '32', '7', '0', '0', 'A2铂金 三段 A2 Premium Toddler', '/uploads/images/20190729/9609c47af27df3c4875605abde9ff4dc.jpg', '', '31.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('31', '10002', '32', '20', '0', '0', 'Blackmores澳佳宝 孕妇黄金素 180粒', '/uploads/images/20190729/63b7cacef160a2699898bda5c231b78f.jpg', '', '30.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('32', '10002', '32', '21', '0', '0', 'Blackmores澳佳宝 叶酸片500mcg 90粒', '/uploads/images/20190729/2808f8c49bc8ef2211cdeb8942968a61.jpg', '', '15.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('33', '10002', '33', '22', '0', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '/uploads/images/20190729/8225facb8669bff2ca9082f3404de33b.jpg', '', '50.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('38', '10002', '65', '22', '0', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', 'http://127.0.0.9/uploads/images/20190729/8225facb8669bff2ca9082f3404de33b.jpg', '', '45.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('39', '10002', '65', '23', '0', '0', 'Swisse 高倍蜂胶2000mg 300粒', 'http://127.0.0.9/uploads/images/20190803/504cb220ecb2a76a9794fa1ccb763b86.jpg', '', '25.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('40', '10002', '66', '10', '0', '0', 'A2铂金 二段 A2 Follow On Formula', 'http://127.0.0.9/uploads/images/20190729/e47cfa2678fc5785d957bb71e4e19f6e.jpg', '', '29.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('41', '10002', '66', '7', '0', '0', 'A2铂金 三段 A2 Premium Toddler', 'http://127.0.0.9/uploads/images/20190729/9609c47af27df3c4875605abde9ff4dc.jpg', '', '31.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('42', '10002', '68', '22', '0', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', 'http://127.0.0.9/uploads/images/20190729/8225facb8669bff2ca9082f3404de33b.jpg', '', '50.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('43', '10002', '69', '9', '0', '0', 'A2铂金 一段 A2 Infant Formula ', 'http://127.0.0.9/uploads/images/20190729/a0312cfa0da34943a7898ee675267dd3.jpg', '', '30.00', '2', '2');
INSERT INTO `pm_order_cart` VALUES ('44', '10002', '71', '9', '0', '0', 'A2铂金 一段 A2 Infant Formula ', 'http://127.0.0.9/uploads/images/20190729/a0312cfa0da34943a7898ee675267dd3.jpg', '', '30.00', '2', '2');

-- ----------------------------
-- Table structure for `pm_order_detail`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order_detail`;
CREATE TABLE `pm_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  `baoguoID` int(50) NOT NULL,
  `goodsID` int(11) NOT NULL,
  `specID` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `short` varchar(200) NOT NULL,
  `number` int(11) NOT NULL COMMENT '单品的数量',
  `price` decimal(8,2) NOT NULL,
  `cancel` tinyint(4) NOT NULL COMMENT '取消订单',
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_detail
-- ----------------------------
INSERT INTO `pm_order_detail` VALUES ('8', '20', '10002', '9', '10', '0', 'A2铂金 二段 A2 Follow On Formula', 'A2-2', '1', '29.00', '0', '1566291433');
INSERT INTO `pm_order_detail` VALUES ('9', '20', '10002', '10', '23', '0', 'Swisse 高倍蜂胶2000mg 300粒', 'SW蜂胶300粒', '1', '23.00', '0', '1566291433');
INSERT INTO `pm_order_detail` VALUES ('10', '20', '10002', '10', '20', '0', 'Blackmores澳佳宝 孕妇黄金素 180粒', 'BM黄金素', '1', '28.00', '0', '1566291433');
INSERT INTO `pm_order_detail` VALUES ('12', '22', '10002', '12', '26', '0', '六件包邮优惠套餐', '鞋子', '3', '180.00', '0', '1566390419');
INSERT INTO `pm_order_detail` VALUES ('13', '22', '10002', '13', '26', '0', '六件包邮优惠套餐', '鞋子', '3', '180.00', '0', '1566390419');
INSERT INTO `pm_order_detail` VALUES ('22', '32', '10002', '22', '22', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '爱乐维', '1', '50.00', '0', '1567529609');
INSERT INTO `pm_order_detail` VALUES ('23', '32', '10002', '23', '10', '0', 'A2铂金 二段 A2 Follow On Formula', 'A2-2', '1', '29.00', '0', '1567529609');
INSERT INTO `pm_order_detail` VALUES ('24', '32', '10002', '23', '7', '0', 'A2铂金 三段 A2 Premium Toddler', 'A3', '1', '31.00', '0', '1567529609');
INSERT INTO `pm_order_detail` VALUES ('25', '32', '10002', '24', '20', '0', 'Blackmores澳佳宝 孕妇黄金素 180粒', 'BM黄金素', '1', '30.00', '0', '1567529609');
INSERT INTO `pm_order_detail` VALUES ('26', '32', '10002', '24', '21', '0', 'Blackmores澳佳宝 叶酸片500mcg 90粒', 'BM叶酸90粒', '1', '15.00', '0', '1567529609');
INSERT INTO `pm_order_detail` VALUES ('27', '33', '10002', '25', '22', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '爱乐维', '1', '50.00', '0', '1567529937');
INSERT INTO `pm_order_detail` VALUES ('32', '65', '10002', '30', '22', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '爱乐维', '1', '45.00', '0', '1567568588');
INSERT INTO `pm_order_detail` VALUES ('33', '65', '10002', '31', '23', '0', 'Swisse 高倍蜂胶2000mg 300粒', 'SW蜂胶300粒', '1', '25.00', '0', '1567568588');
INSERT INTO `pm_order_detail` VALUES ('34', '66', '10002', '32', '10', '0', 'A2铂金 二段 A2 Follow On Formula', 'A2-2', '1', '29.00', '0', '1567568588');
INSERT INTO `pm_order_detail` VALUES ('35', '66', '10002', '32', '7', '0', 'A2铂金 三段 A2 Premium Toddler', 'A3', '1', '31.00', '0', '1567568588');
INSERT INTO `pm_order_detail` VALUES ('36', '67', '10002', '33', '22', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '爱乐维', '1', '50.00', '0', '1567577260');
INSERT INTO `pm_order_detail` VALUES ('37', '68', '10002', '34', '22', '0', 'Elevit 女士爱乐维 孕期维生素 100粒', '爱乐维', '1', '50.00', '0', '1567577435');
INSERT INTO `pm_order_detail` VALUES ('38', '69', '10002', '35', '9', '0', 'A2铂金 一段 A2 Infant Formula ', 'A1', '2', '30.00', '0', '1567577435');
INSERT INTO `pm_order_detail` VALUES ('39', '71', '10002', '36', '9', '0', 'A2铂金 一段 A2 Infant Formula ', 'A1', '2', '30.00', '0', '1567577435');

-- ----------------------------
-- Table structure for `pm_role`
-- ----------------------------
DROP TABLE IF EXISTS `pm_role`;
CREATE TABLE `pm_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_role
-- ----------------------------
INSERT INTO `pm_role` VALUES ('1', '超级管理员', null, '1', '拥有所有权限');
INSERT INTO `pm_role` VALUES ('2', '查看报告组', null, '1', '查看报告组');

-- ----------------------------
-- Table structure for `pm_role_user`
-- ----------------------------
DROP TABLE IF EXISTS `pm_role_user`;
CREATE TABLE `pm_role_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_sender`
-- ----------------------------
DROP TABLE IF EXISTS `pm_sender`;
CREATE TABLE `pm_sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_sender
-- ----------------------------
INSERT INTO `pm_sender` VALUES ('1', '2', 'jack', '18523651112');
INSERT INTO `pm_sender` VALUES ('2', '10001', '李四', '135000000');
INSERT INTO `pm_sender` VALUES ('3', '10001', '小明', '185000000');
INSERT INTO `pm_sender` VALUES ('4', '10001', '王帅', '1350000001');
INSERT INTO `pm_sender` VALUES ('6', '10001', '张扬', '185000000');
INSERT INTO `pm_sender` VALUES ('8', '10002', '张三', '13500000000');

-- ----------------------------
-- Table structure for `pm_server`
-- ----------------------------
DROP TABLE IF EXISTS `pm_server`;
CREATE TABLE `pm_server` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `short` varchar(20) NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `sort` int(3) unsigned NOT NULL DEFAULT '50',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_server
-- ----------------------------
INSERT INTO `pm_server` VALUES ('1', '奶粉默认包泡泡，无需备注，免费！', '', '0.00', '50', '1540991368', '1544578530');
INSERT INTO `pm_server` VALUES ('2', '+ 奶粉标记（0.3刀/个），送打包照片！', '签名', '0.30', '50', '1540991387', '1556629370');
INSERT INTO `pm_server` VALUES ('5', '+ 锡纸 按件计价', '锡纸', '0.25', '50', '1540991449', '1556264805');
INSERT INTO `pm_server` VALUES ('6', '+ 奶粉爆罐/袋保险（每罐/袋3刀，限代发）', '保险', '3.00', '50', '1540991468', '1556264798');
INSERT INTO `pm_server` VALUES ('17', '+铁元破损保险（每瓶3刀，限代发）', '保险', '3.00', '50', '1553674823', '1556264899');

-- ----------------------------
-- Table structure for `pm_shop`
-- ----------------------------
DROP TABLE IF EXISTS `pm_shop`;
CREATE TABLE `pm_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cityID` int(4) NOT NULL,
  `cate` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `picname` varchar(200) DEFAULT NULL,
  `linkman` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `intr` varchar(200) NOT NULL,
  `mp4` varchar(300) DEFAULT NULL,
  `image` text NOT NULL,
  `content` text NOT NULL,
  `py` varchar(10) NOT NULL,
  `submit` tinyint(4) NOT NULL,
  `edit` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_shop
-- ----------------------------
INSERT INTO `pm_shop` VALUES ('7', '1', '1,4,5,7', '测试店铺', 'test', 'e10adc3949ba59abbe56e057f20f883e', '/uploads/images/20190902/bc2b91794194d610b4d15c495735928f.jpg', 'jack', '阿德莱德长安大街11号', '13500000000', '一家很不错的商店', '', '/uploads/images/20190822/292fc46c8c0fe690c4b7f4acbaf56fed.jpg,/uploads/images/20190822/86708d2a1662fa3ec163fbc1ab34af6d.jpg', '这个店铺\n嗯嗯\n很澳洲', 'C', '0', '0', '1', '1566488164', '1567425920');
INSERT INTO `pm_shop` VALUES ('8', '2', '1,4,7', '奶粉专卖店', 'test1', 'e10adc3949ba59abbe56e057f20f883e', '/uploads/images/20190903/4d231b670020a7f8a62c13dd86291cd3.jpg', '赵柳', '阿萨德饭大是大非ad', '18700001111', '我们只卖一种产品', '', '', '', 'N', '0', '0', '1', '1567475240', '1567475240');

-- ----------------------------
-- Table structure for `pm_shop_fav`
-- ----------------------------
DROP TABLE IF EXISTS `pm_shop_fav`;
CREATE TABLE `pm_shop_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_shop_fav
-- ----------------------------
INSERT INTO `pm_shop_fav` VALUES ('4', '20', '2');
INSERT INTO `pm_shop_fav` VALUES ('5', '24', '2');
INSERT INTO `pm_shop_fav` VALUES ('6', '18', '2');
INSERT INTO `pm_shop_fav` VALUES ('7', '15', '2');
INSERT INTO `pm_shop_fav` VALUES ('15', '19', '10001');
INSERT INTO `pm_shop_fav` VALUES ('17', '7', '10001');
INSERT INTO `pm_shop_fav` VALUES ('18', '7', '10002');

-- ----------------------------
-- Table structure for `pm_sign`
-- ----------------------------
DROP TABLE IF EXISTS `pm_sign`;
CREATE TABLE `pm_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberID` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `signDate` varchar(20) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_sign
-- ----------------------------
INSERT INTO `pm_sign` VALUES ('1', '2', '10', '2019-07-23', '1563892114');
INSERT INTO `pm_sign` VALUES ('2', '2', '10', '2019-08-06', '1565097687');

-- ----------------------------
-- Table structure for `pm_user`
-- ----------------------------
DROP TABLE IF EXISTS `pm_user`;
CREATE TABLE `pm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `token_out` int(11) DEFAULT NULL,
  `group` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_user
-- ----------------------------
INSERT INTO `pm_user` VALUES ('1', 'admin', '64def183c8846acf3f9e13799e627a17', '管理员', '', '1400117147', '1490074945', '567c4928987b5d4669b90e9ceb32a98c02fe21a8', '1555749200', '1', '1');

-- ----------------------------
-- Table structure for `pm_user_log`
-- ----------------------------
DROP TABLE IF EXISTS `pm_user_log`;
CREATE TABLE `pm_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `loginTime` int(10) NOT NULL,
  `loginIP` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_user_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_wuliu`
-- ----------------------------
DROP TABLE IF EXISTS `pm_wuliu`;
CREATE TABLE `pm_wuliu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌表',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌地址',
  `price` decimal(8,2) NOT NULL,
  `otherPrice` decimal(8,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `sort` int(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_wuliu
-- ----------------------------
INSERT INTO `pm_wuliu` VALUES ('3', '澳邮', 'www.baidu.com', '6.00', '2.00', '3.00', '50', '1541251318', '1556197553');
