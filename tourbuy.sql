/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : tourbuy

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-10-24 17:34:18
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
  `goodsID` int(11) NOT NULL,
  `sort` int(11) NOT NULL COMMENT '排序',
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_ad
-- ----------------------------
INSERT INTO `pm_ad` VALUES ('1', '1', '0-1-', '测试广告', '/uploads/images/20190906/f1e66c82b163b7244b60b05bed837e6a.jpg', '111', '0', '50', '1563768353', '1567739847');
INSERT INTO `pm_ad` VALUES ('2', '1', '0-1-', '测试广告', '/uploads/images/20190906/a4f19a99293c639fef0e07454138f304.jpg', '111', '0', '50', '1563768399', '1567739881');
INSERT INTO `pm_ad` VALUES ('3', '1', '0-1-', 'swisse广告', '/uploads/images/20190906/0bf6e6ca6d8aee777d883bbcd6e49572.jpg', '1', '0', '50', '1563788399', '1567739956');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_address
-- ----------------------------
INSERT INTO `pm_address` VALUES ('1', '10001', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', '', '', '', '0');
INSERT INTO `pm_address` VALUES ('2', '10002', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '2222222', '1');
INSERT INTO `pm_address` VALUES ('3', '10002', '张明', '18500000000', '辽宁省', '大连市', '旅顺口区', '石鼓路331号西', '', '', '', '0');
INSERT INTO `pm_address` VALUES ('4', '10003', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', '', '', '', '1');
INSERT INTO `pm_address` VALUES ('5', '10001', '测试', '13206485795', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', '', '', '', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_cart
-- ----------------------------
INSERT INTO `pm_cart` VALUES ('19', '2', '0', '10', '0', '1', '1', '1');
INSERT INTO `pm_cart` VALUES ('18', '2', '0', '19', '0', '1', '1', '1');
INSERT INTO `pm_cart` VALUES ('20', '2', '0', '20', '0', '3', '3', '4');
INSERT INTO `pm_cart` VALUES ('105', '10012', '8', '1', '0', '2', '2', '1');
INSERT INTO `pm_cart` VALUES ('104', '10012', '8', '3', '0', '1', '1', '1');
INSERT INTO `pm_cart` VALUES ('103', '10003', '8', '1', '0', '1', '1', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_city
-- ----------------------------
INSERT INTO `pm_city` VALUES ('1', '阿德莱德', '1566486859', '1571882390');
INSERT INTO `pm_city` VALUES ('2', '悉尼', '1566486870', '1571800086');
INSERT INTO `pm_city` VALUES ('3', '布里斯班', '1570445651', '1571800091');
INSERT INTO `pm_city` VALUES ('4', '霍巴特', '1570447492', '1570447536');

-- ----------------------------
-- Table structure for `pm_city_express`
-- ----------------------------
DROP TABLE IF EXISTS `pm_city_express`;
CREATE TABLE `pm_city_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cityID` int(11) DEFAULT NULL,
  `expressID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_city_express
-- ----------------------------
INSERT INTO `pm_city_express` VALUES ('20', '1', '4');
INSERT INTO `pm_city_express` VALUES ('18', '2', '4');
INSERT INTO `pm_city_express` VALUES ('21', '1', '5');
INSERT INTO `pm_city_express` VALUES ('19', '3', '4');
INSERT INTO `pm_city_express` VALUES ('16', '4', '4');

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
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_config
-- ----------------------------
INSERT INTO `pm_config` VALUES ('1', 'name', '途买', 'basic', '');
INSERT INTO `pm_config` VALUES ('2', 'logo', '', 'basic', '');
INSERT INTO `pm_config` VALUES ('3', 'isClose', '0', 'basic', '');
INSERT INTO `pm_config` VALUES ('4', 'closeInfo', '系统维护中', 'basic', '');
INSERT INTO `pm_config` VALUES ('5', 'domain', 'http://www.tourbuy.net', 'basic', '');
INSERT INTO `pm_config` VALUES ('6', 'copyright', '途买', 'basic', '');
INSERT INTO `pm_config` VALUES ('7', 'email', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('8', 'weixin', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('9', 'weibo', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('10', 'description', '途买 Tourbuy', 'basic', '');
INSERT INTO `pm_config` VALUES ('11', 'qrcode', '', 'basic', '');
INSERT INTO `pm_config` VALUES ('19', 'mobile', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('12', 'address', '#', 'basic', '');
INSERT INTO `pm_config` VALUES ('13', 'tel', '010-23190228', 'basic', '');
INSERT INTO `pm_config` VALUES ('15', 'qq', '1826366140', 'basic', '');
INSERT INTO `pm_config` VALUES ('16', 'keywords', '途买 Tourbuy', 'basic', '');
INSERT INTO `pm_config` VALUES ('18', 'title', '途买', 'basic', '');
INSERT INTO `pm_config` VALUES ('82', 'sign', '10', 'member', null);
INSERT INTO `pm_config` VALUES ('21', 'safecode', '123456', 'basic', '');
INSERT INTO `pm_config` VALUES ('83', 'huilv', '4.29', 'member', null);
INSERT INTO `pm_config` VALUES ('84', 'orderTime', '48', 'member', null);
INSERT INTO `pm_config` VALUES ('85', 'register', '50', 'member', null);
INSERT INTO `pm_config` VALUES ('86', 'buy', '200', 'member', null);
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
INSERT INTO `pm_config` VALUES ('51', 'APP_ID', 'wx3ee3b1aa9812c5da', 'weixin', null);
INSERT INTO `pm_config` VALUES ('52', 'APP_SECRET', '21b03d572c4ff02f5cfea4e4807b04d6', 'weixin', null);
INSERT INTO `pm_config` VALUES ('53', 'MCH_KEY', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('54', 'MCH_ID', '', 'weixin', null);
INSERT INTO `pm_config` VALUES ('132', 'SUPAY_ID', '201567669355', 'supay', null);
INSERT INTO `pm_config` VALUES ('133', 'SUPAY_KEY', '68bc9477d0f2c908eed9498bf926d89e', 'supay', null);
INSERT INTO `pm_config` VALUES ('96', 'back5', '25', 'member', null);
INSERT INTO `pm_config` VALUES ('63', 'linkman', '#', 'basic', null);
INSERT INTO `pm_config` VALUES ('64', 'fax', '0371-23190098', 'basic', null);
INSERT INTO `pm_config` VALUES ('97', 'min', '0.01', 'member', null);
INSERT INTO `pm_config` VALUES ('98', 'max', '2.00', 'member', null);
INSERT INTO `pm_config` VALUES ('99', 'hour', '24', 'member', null);
INSERT INTO `pm_config` VALUES ('100', 'shareMax', '20', 'member', null);
INSERT INTO `pm_config` VALUES ('101', 'isReg', '1', 'member', null);
INSERT INTO `pm_config` VALUES ('102', 'hotkey', '保湿面膜', 'member', null);
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
INSERT INTO `pm_config` VALUES ('134', 'beishu', '1000', 'member', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pm_coupon
-- ----------------------------
INSERT INTO `pm_coupon` VALUES ('3', '8', '奶粉专卖店', '新手券', '立减50元', '1', '0', '50', '1', '1', '100', '', '使用说明啊啊啊', '1568958255', '1551964995');
INSERT INTO `pm_coupon` VALUES ('5', '7', '测试店铺', '测试一下', '满50元立减5元', '1', '50', '5', '1', '1', '30', '', '测试', '1568958249', '1565698449');
INSERT INTO `pm_coupon` VALUES ('6', '7', '测试店铺', '123123', '立减1元', '0', '0', '1', '1', '1', '1', '', '测试', '1568958242', '1567501406');
INSERT INTO `pm_coupon` VALUES ('7', '9', '水光针专卖', '我是优惠券', '立减20元', '0', '0', '20', '1', '1', '100', '', '测试', '1568958235', '1568958210');

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
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_coupon_log
-- ----------------------------
INSERT INTO `pm_coupon_log` VALUES ('40', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '4413836228', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('31', '7', '10001', '?化魄?', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '7857832999', '0', '0', '1572570589', '1569978589');
INSERT INTO `pm_coupon_log` VALUES ('30', '8', '10001', '?化魄?', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '1084837634', '0', '0', '1578618589', '1569978589');
INSERT INTO `pm_coupon_log` VALUES ('35', '8', '10002', '東', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '9924776136', '0', '0', '1578620152', '1569980152');
INSERT INTO `pm_coupon_log` VALUES ('36', '7', '10002', '東', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '5557587417', '0', '0', '1572572152', '1569980152');
INSERT INTO `pm_coupon_log` VALUES ('37', '8', '10003', 'Eric Yao', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '6488155338', '0', '0', '1578627075', '1569987075');
INSERT INTO `pm_coupon_log` VALUES ('38', '7', '10003', 'Eric Yao', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '4601703587', '0', '0', '1572579075', '1569987075');
INSERT INTO `pm_coupon_log` VALUES ('39', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '8387166240', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('33', '8', '10000', '月明', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '6260306766', '0', '0', '1578619117', '1569979117');
INSERT INTO `pm_coupon_log` VALUES ('34', '7', '10000', '月明', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '8321836731', '0', '0', '1572571117', '1569979117');
INSERT INTO `pm_coupon_log` VALUES ('41', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '5341623545', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('42', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '9358957116', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('43', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '3245787335', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('44', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '6903590396', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('45', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '6985104895', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('46', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '4988003967', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('47', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '7240059876', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('48', '9', '0', '', '7', '我是优惠券', '立减20元', '0.00', '20.00', '测试', '', '7122766389', '0', '0', '0', '1569989426');
INSERT INTO `pm_coupon_log` VALUES ('49', '8', '10004', 'mikel??⁷⁰', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '1367833110', '0', '0', '1578630133', '1569990133');
INSERT INTO `pm_coupon_log` VALUES ('50', '7', '10004', 'mikel??⁷⁰', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '1960218841', '0', '0', '1572582133', '1569990133');
INSERT INTO `pm_coupon_log` VALUES ('51', '7', '10001', '?化魄?', '6', '123123', '立减1元', '0.00', '1.00', '测试', '', '6249816760', '0', '0', '1570363860', '1570277460');
INSERT INTO `pm_coupon_log` VALUES ('52', '8', '10005', '月明', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '1574854610', '0', '0', '1578986469', '1570346469');
INSERT INTO `pm_coupon_log` VALUES ('53', '7', '10005', '月明', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '4421685867', '0', '0', '1572938469', '1570346469');
INSERT INTO `pm_coupon_log` VALUES ('54', '7', '10002', '東', '6', '123123', '立减1元', '0.00', '1.00', '测试', '', '9939865485', '0', '0', '1570513648', '1570427248');
INSERT INTO `pm_coupon_log` VALUES ('55', '8', '10006', '阿德眼代发平台客服', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '2403352992', '0', '0', '1579081894', '1570441894');
INSERT INTO `pm_coupon_log` VALUES ('56', '7', '10006', '阿德眼代发平台客服', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '1536262870', '0', '0', '1573033894', '1570441894');
INSERT INTO `pm_coupon_log` VALUES ('57', '8', '10007', 'A安尔捷澳洲奶粉保健品批发', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '2122326571', '0', '0', '1579081951', '1570441951');
INSERT INTO `pm_coupon_log` VALUES ('58', '7', '10007', 'A安尔捷澳洲奶粉保健品批发', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '4254205912', '0', '0', '1573033951', '1570441951');
INSERT INTO `pm_coupon_log` VALUES ('59', '8', '10008', 'Aaron陈仲杰', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '7412397656', '0', '0', '1579087342', '1570447342');
INSERT INTO `pm_coupon_log` VALUES ('60', '7', '10008', 'Aaron陈仲杰', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '4034236253', '0', '0', '1573039342', '1570447342');
INSERT INTO `pm_coupon_log` VALUES ('61', '8', '10009', 'Sumanation?', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '1717562228', '0', '0', '1579087940', '1570447940');
INSERT INTO `pm_coupon_log` VALUES ('62', '7', '10009', 'Sumanation?', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '7085502820', '0', '0', '1573039940', '1570447940');
INSERT INTO `pm_coupon_log` VALUES ('63', '8', '10010', '苍耳', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '5260402732', '0', '0', '1579091478', '1570451478');
INSERT INTO `pm_coupon_log` VALUES ('64', '7', '10010', '苍耳', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '9035513391', '0', '0', '1573043478', '1570451478');
INSERT INTO `pm_coupon_log` VALUES ('65', '8', '10011', '摴醴訾', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '5719444452', '0', '0', '1579133023', '1570493023');
INSERT INTO `pm_coupon_log` VALUES ('66', '7', '10011', '摴醴訾', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '4999238810', '0', '0', '1573085023', '1570493023');
INSERT INTO `pm_coupon_log` VALUES ('67', '8', '10012', 'Cindy ^_^', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '2392103509', '0', '0', '1579179577', '1570539577');
INSERT INTO `pm_coupon_log` VALUES ('68', '7', '10012', 'Cindy ^_^', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '3861445209', '0', '0', '1573131577', '1570539577');
INSERT INTO `pm_coupon_log` VALUES ('69', '8', '10013', 'eleven-nono', '3', '新手券', '立减50元', '0.00', '50.00', '使用说明啊啊啊', '', '4150525139', '0', '0', '1579515679', '1570875679');
INSERT INTO `pm_coupon_log` VALUES ('70', '7', '10013', 'eleven-nono', '5', '测试一下', '满50元立减5元', '50.00', '5.00', '测试', '', '7646722954', '0', '0', '1573467679', '1570875679');

-- ----------------------------
-- Table structure for `pm_express`
-- ----------------------------
DROP TABLE IF EXISTS `pm_express`;
CREATE TABLE `pm_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `price` decimal(4,2) DEFAULT NULL,
  `weight` decimal(4,1) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_express
-- ----------------------------
INSERT INTO `pm_express` VALUES ('4', 'EWE', '180', '6.50', '4.0', 'EWE', '1571881702', '1570447520');
INSERT INTO `pm_express` VALUES ('5', '4PX', '1', '1.00', '4.0', 'PX', '1571881694', '1570447550');

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
  `cityID` int(11) NOT NULL,
  `shopID` int(11) NOT NULL,
  `group` int(11) NOT NULL COMMENT '0普通商品 1特惠商品',
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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_flash
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_goods`
-- ----------------------------
DROP TABLE IF EXISTS `pm_goods`;
CREATE TABLE `pm_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` tinyint(4) NOT NULL COMMENT '0普通商品1特惠商品',
  `shopID` int(11) NOT NULL,
  `shopName` varchar(200) NOT NULL,
  `cityID` int(11) NOT NULL,
  `expressID` int(11) NOT NULL,
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
  `jiesuan` decimal(10,2) NOT NULL COMMENT '结算价',
  `gst` decimal(4,2) NOT NULL COMMENT '含税税率',
  `weight` decimal(10,2) NOT NULL,
  `wuliuWeight` decimal(10,2) NOT NULL,
  `sellNumber` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `number` int(11) NOT NULL COMMENT '单品的数量，如果是3件s商品的套餐，就填写3',
  `comm` tinyint(4) NOT NULL COMMENT '是否推荐',
  `baoyou` tinyint(4) NOT NULL COMMENT '包邮',
  `flash` tinyint(4) NOT NULL COMMENT '抢购',
  `tehui` tinyint(4) NOT NULL COMMENT '特惠',
  `jingpin` tinyint(4) NOT NULL COMMENT '每日精品',
  `ziti` tinyint(4) NOT NULL,
  `show` tinyint(11) NOT NULL COMMENT '是否显示',
  `sort` int(11) NOT NULL,
  `createDate` varchar(20) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods
-- ----------------------------
INSERT INTO `pm_goods` VALUES ('1', '0', '8', 'Chemist Warehouse', '2', '4', '0', '32', '0-4-31-32-', '0', '', '1', '0', '4', '罐装奶粉', 'UNIQLO', '优衣库', '含A2型蛋白质 自然而珍贵', '', '', '/uploads/images/20190920/89c9985bb4d7253b32edea2183e82706.jpg', null, '<p>无缝设计的高级轻型羽绒系列，穿着感温暖！具有立体感的兜帽，兼具防寒性与设计感。</p>\n<p>&middot;是一款时尚又动感的单品。</p>\n<p>&middot;施有持久防水加工，有助于适度抵挡雨水侵袭。</p>\n<p>&middot;表面采用质地轻薄、不易拉断、穿着感轻盈、具有耐久性的锦纶面料制成。</p>\n<p>&middot;袖部设计考究，便于活动。</p>\n<p>&middot;对腰部细节进行了适度调整，呈现出利落外观。</p>\n<p>&middot;兜帽附带调节器，有助于调节贴合度。</p>\n<p>※该商品并非完全无缝，仍有部分绗缝。</p>\n<p>持久防水/耐久防水※通过对面料进行防水加工，使服装表面具备反弹雨滴等水滴的抗沾湿性能（但不具备防渗透性能）。因面料表面的防水剂附着的较为牢固，即使产品经多次穿着（或使用）及洗涤，其防水性仍然存在（但随着洗涤次数的增加，该性能将逐渐减弱）。</p>\n<p>【面料组成】[03 灰色、09 黑色、15 珊瑚红色、55 青绿色、65 宝蓝色、69 藏青色]面料：锦纶100％。里料：锦纶100％。含绒量：90％。</p>\n<p>【洗涤信息】手洗</p>\n<p>此商品在商品完好，符合相关退换货规则的前提下支持七天无理由退换货。</p>', '', '79', '20', '58.20', '79.00', '3.00', '60.00', '0.00', '1.00', '1.10', '33', '997', '1', '1', '0', '0', '1', '1', '0', '1', '50', '2019-09-06', '1567741565', '1568955283');
INSERT INTO `pm_goods` VALUES ('2', '0', '8', 'Chemist Warehouse', '2', '4', '0', '32', '0-4-31-32-', '0', '', '2', '0', '4', '袋装奶粉', 'UNIQLO', '222', '含A2型蛋白质 自然而珍贵', '', '', '/uploads/images/20190920/2d0d6b3816cbfe1acf4c930beb29231d.jpg', null, '', '', '37', '10', '24.25', '37.00', '3.00', '25.00', '3.00', '1.00', '1.10', '0', '999', '1', '1', '1', '0', '1', '0', '0', '1', '50', '2019-09-06', '1567741717', '1568956021');
INSERT INTO `pm_goods` VALUES ('3', '0', '8', 'Chemist Warehouse', '2', '4', '0', '32', '0-4-31-32-', '0', '', '3', '0', '4', '保健品/食品', 'AA', '文胸', '含A2型蛋白质 自然而珍贵', '', '款式设计简约，面料舒服透气，无钢圈的穿着舒服不勒，很塑型，穿上没有束缚感非常舒服', '/uploads/images/20190920/139476be76497500208c94258527e750.jpg', null, '', '', '24', '10', '18.05', '24.00', '5.00', '19.00', '3.00', '0.50', '0.60', '0', '999', '1', '1', '1', '0', '1', '0', '0', '1', '50', '2019-09-06', '1567742155', '1568955482');
INSERT INTO `pm_goods` VALUES ('4', '0', '8', 'Chemist Warehouse', '2', '4', '0', '17', '0-1-10-17-', '0', '', '4', '0', '9', '洗护类', 'UNIQLO', '毛呢大衣', '含A2型蛋白质 自然而珍贵', '', '整体不错，此款大衣颜色正，是我想要的颜色，应该是羊毛的。这个版型属于宽松版型的，本人160，重106斤，穿xs码的还行', '/uploads/images/20190920/2fb79348c9dac48e4c33511cc3a9072e.jpg', null, '', '', '99', '20', '77.60', '99.00', '3.00', '80.00', '0.00', '1.50', '1.70', '0', '999', '1', '1', '0', '0', '1', '1', '0', '1', '50', '2019-09-06', '1567742308', '1568955533');
INSERT INTO `pm_goods` VALUES ('5', '0', '7', 'TerryWhite', '1', '4', '0', '36', '0-4-31-36-', '0', '', '5', '0', '11', '日用品/杂货', 'AAAAAA', '贝拉米有机婴儿米粉', '新西兰纯天然奶源', '', '', '/uploads/images/20190920/71e3a4f76aab8f6e6fbe58931a730625.jpg', null, '', '2020年10月', '19', '5', '14.55', '19.00', '3.00', '15.00', '0.00', '1.00', '1.10', '20', '999', '1', '1', '0', '0', '1', '0', '0', '1', '50', '2019-09-10', '1568127803', '1568962985');
INSERT INTO `pm_goods` VALUES ('6', '0', '7', 'TerryWhite', '1', '4', '0', '36', '0-4-31-36-', '0', '', '6', '0', '5', '化妆品', '11', '1', '新西兰纯天然奶源', '', '', '/uploads/images/20190920/1bbca64c5a9bd5dddb23d130132a0cf0.jpg', null, '', '', '0', '0', '28.50', '20.00', '5.00', '30.00', '0.00', '1.10', '1.10', '0', '999', '1', '1', '0', '0', '1', '1', '0', '1', '50', '2019-09-20', '1568955749', '1568962975');
INSERT INTO `pm_goods` VALUES ('7', '0', '7', 'TerryWhite', '1', '4', '0', '57', '0-4-56-57-', '0', '', '7', '0', '18', '1个不混', 'Bio Island 婴幼儿乳钙 奶钙 90粒', '12', '天然液体乳钙', '', '', '/uploads/images/20190920/3cdceefd115675dc0e9a9e9627af9929.jpg', null, '', '', '0', '0', '27.60', '50.00', '8.00', '30.00', '0.00', '0.12', '0.18', '0', '998', '1', '1', '1', '0', '1', '1', '0', '1', '50', '2019-09-20', '1568955936', '1571883863');
INSERT INTO `pm_goods` VALUES ('8', '0', '9', '水光针专卖', '1', '5', '0', '69', '0-7-67-69-', '0', '', '1', '0', '25', '罐装奶粉1', 'Eaoron 补水面膜（白）5片/盒', '122', '澳洲推荐品牌', '', '', '/uploads/images/20190920/a498288cef351426a62e63f885da0b8c.jpg', null, '', '', '0', '0', '23.75', '20.00', '5.00', '25.00', '0.00', '0.20', '0.25', '0', '999', '1', '1', '1', '0', '1', '1', '0', '1', '50', '2019-09-20', '1568957761', '1571883399');
INSERT INTO `pm_goods` VALUES ('9', '0', '9', '水光针专卖', '1', '4', '0', '71', '0-7-67-71-', '0', '', '2', '0', '25', '包邮商品1', 'Eaoron 水光针眼霜 15g', 'Eaoron 水光针眼霜 15g', '澳洲最佳品牌推荐', '', '', '/uploads/images/20190920/f02c2d8dc1dacafe2794668155e3160c.jpg', null, '', '', '0', '0', '24.25', '20.00', '3.00', '25.00', '0.00', '0.20', '0.25', '0', '998', '1', '1', '0', '0', '1', '1', '0', '1', '50', '2019-09-20', '1568958010', '1568963148');
INSERT INTO `pm_goods` VALUES ('10', '0', '10', 'Natio 系列专卖', '2', '4', '0', '77', '0-7-65-77-', '0', '', '6', '0', '43', '包邮商品2', 'Natio 洋甘菊爽肤水 250ml', 'Natio 洋甘菊爽肤水 250ml', '纯净天然的护肤品牌', '纯净天然的护肤品牌', '纯净天然的护肤品牌', '/uploads/images/20190920/086a052a10d6819fdc7e54009be1e079.png', null, '', '', '0', '0', '14.55', '20.00', '3.00', '15.00', '0.00', '0.30', '0.40', '0', '999', '1', '1', '1', '0', '1', '0', '0', '1', '50', '2019-09-20', '1568962697', '1571882891');
INSERT INTO `pm_goods` VALUES ('11', '0', '10', 'Natio 系列专卖', '2', '4', '0', '68', '0-7-68-', '0', '', '4', '0', '43', '洗护类2', 'Natio 亮白眼霜 20g', 'Natio 亮白眼霜 20g', '纯净天然的护肤品牌', '纯净天然的护肤品牌', '纯净天然的护肤品牌', '/uploads/images/20190920/ca7d47ee5ab218d5ac6019c6f0a4cc51.jpg', null, '', '', '0', '0', '38.00', '0.01', '5.00', '40.00', '0.00', '0.20', '0.40', '0', '999', '1', '1', '1', '0', '1', '0', '0', '1', '50', '2019-09-20', '1568962817', '1571882519');
INSERT INTO `pm_goods` VALUES ('12', '0', '7', 'TerryWhite', '1', '5', '0', '15', '0-1-10-15-', '0', '', '1', '0', '5', '红酒1', '111', '11', '22', '1', '1', '2', null, '', '1', '0', '0', '66.50', '100.00', '5.00', '70.00', '3.00', '1.00', '1.00', '0', '999', '1', '0', '0', '0', '0', '0', '0', '1', '50', '2019-10-24', '1571904264', '1571908033');
INSERT INTO `pm_goods` VALUES ('13', '0', '7', 'TerryWhite', '1', '5', '0', '15', '0-1-10-15-', '0', '', '1', '0', '5', '红酒一箱', '11', '1', '1', '11', '1', '1', null, '', '', '0', '0', '380.00', '500.00', '5.00', '400.00', '3.00', '1.00', '1.00', '0', '999', '6', '0', '0', '0', '0', '0', '0', '1', '50', '2019-10-24', '1571904315', '1571908022');

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
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_cate
-- ----------------------------
INSERT INTO `pm_goods_cate` VALUES ('1', '0', '女装', '', '50', '0-1-', '/uploads/images/20190920/f4470d49877e8ee9098501c928a26b90.jpg', '', '0', '1', '0', '', '', '1567736719', '1568948468');
INSERT INTO `pm_goods_cate` VALUES ('2', '0', '男装', '', '50', '0-2-', '/uploads/images/20190920/97a874aa2eb421d9a45f7c006ad8dad8.png', '', '0', '1', '0', '', '', '1567736735', '1568948665');
INSERT INTO `pm_goods_cate` VALUES ('3', '0', '保健品', '', '3', '0-3-', '/uploads/images/20190906/984c0cf6d491bb422323244fe46bd38c.jpg', '', '0', '1', '0', '', '', '1567736748', '1568958314');
INSERT INTO `pm_goods_cate` VALUES ('4', '0', '母婴', '', '1', '0-4-', '/uploads/images/20190906/c35e2a0ca65740290ca44254b9483205.jpg', '', '0', '1', '1', '', '', '1567736755', '1568950713');
INSERT INTO `pm_goods_cate` VALUES ('5', '0', '酒水', '', '50', '0-5-', '/uploads/images/20190906/6d65432fc00a75aa559680216f3a4dda.jpg', '', '0', '1', '0', '', '', '1567736788', '1567740393');
INSERT INTO `pm_goods_cate` VALUES ('6', '0', '旅游', '', '50', '0-6-', '/uploads/images/20190906/c8f7c99067b140b6d0bc4b164dcda9d3.jpg', '', '0', '1', '0', '', '', '1567736796', '1567740428');
INSERT INTO `pm_goods_cate` VALUES ('7', '0', '美妆', '', '2', '0-7-', '/uploads/images/20190906/ffa39895c107bacbed0913ae2921aea6.jpg', '', '0', '1', '1', '', '', '1567736820', '1568958301');
INSERT INTO `pm_goods_cate` VALUES ('8', '0', '生鲜', '', '50', '0-8-', '/uploads/images/20190906/df786732e80a99151745f3fa9860a086.jpg', '', '0', '1', '0', '', '', '1567736835', '1567740508');
INSERT INTO `pm_goods_cate` VALUES ('9', '0', '鞋靴', '', '50', '0-9-', '/uploads/images/20190906/5943b19c7ae0b1611df51e874b198962.jpg', '', '0', '1', '0', '', '', '1567736858', '1567740544');
INSERT INTO `pm_goods_cate` VALUES ('10', '1', '流行趋势', '', '50', '0-1-10-', '', '', '0', '0', '0', '', '', '1567736962', '1567736962');
INSERT INTO `pm_goods_cate` VALUES ('11', '1', '性感内衣', '', '50', '0-1-11-', '', '', '0', '0', '0', '', '', '1567736978', '1567736978');
INSERT INTO `pm_goods_cate` VALUES ('12', '1', '文胸', '', '50', '0-1-12-', '', '', '0', '0', '0', '', '', '1567736993', '1567736993');
INSERT INTO `pm_goods_cate` VALUES ('13', '1', '丝袜', '', '50', '0-1-13-', '', '', '0', '0', '0', '', '', '1567737014', '1567737014');
INSERT INTO `pm_goods_cate` VALUES ('14', '1', '家居服', '', '50', '0-1-14-', '', '', '0', '0', '0', '', '', '1567737035', '1567737035');
INSERT INTO `pm_goods_cate` VALUES ('15', '10', '羽绒服', '', '50', '0-1-10-15-', '/uploads/images/20190906/9c758eecc283f107a2d31fcefa8f7f21.jpg', '', '0', '0', '0', '', '', '1567737065', '1567775181');
INSERT INTO `pm_goods_cate` VALUES ('16', '10', '连衣裙', '', '50', '0-1-10-16-', '/uploads/images/20190906/0846a910fe0cc8c58bc09f9b57a3ebbf.jpg', '', '0', '0', '0', '', '', '1567738378', '1567775291');
INSERT INTO `pm_goods_cate` VALUES ('17', '10', '毛呢大衣', '', '50', '0-1-10-17-', '/uploads/images/20190906/42e31c99c9240d3fd52aca3f3597cc89.jpg', '', '0', '0', '0', '', '', '1567738491', '1567775336');
INSERT INTO `pm_goods_cate` VALUES ('18', '10', '新品', '', '50', '0-1-10-18-', '/uploads/images/20190906/ca2411c05cb6b39469bfe98a117c247e.jpg', '', '0', '0', '0', '', '', '1567738516', '1567775413');
INSERT INTO `pm_goods_cate` VALUES ('19', '11', '性感诱惑', '', '50', '0-1-11-19-', '/uploads/images/20190906/4f804ebec222e3cd2691c36743971b08.jpg', '', '0', '0', '0', '', '', '1567738552', '1567775549');
INSERT INTO `pm_goods_cate` VALUES ('20', '11', '甜美新品', '', '50', '0-1-11-20-', '/uploads/images/20190906/73a3183557763cc63aeed7ac3d726719.jpg', '', '0', '0', '0', '', '', '1567738570', '1567775619');
INSERT INTO `pm_goods_cate` VALUES ('21', '12', '蕾丝内衣', '', '50', '0-1-12-21-', '/uploads/images/20190906/55de5ac48427318e86c628d3c38dc7c4.jpg', '', '0', '0', '0', '', '', '1567738587', '1567775871');
INSERT INTO `pm_goods_cate` VALUES ('22', '12', '运动文胸', '', '50', '0-1-12-22-', '/uploads/images/20190906/debb07f515e1c4dd8706b2ae79d137af.jpg', '', '0', '0', '0', '', '', '1567738605', '1567775925');
INSERT INTO `pm_goods_cate` VALUES ('23', '12', '聚拢文胸', '', '50', '0-1-12-23-', '/uploads/images/20190906/f0f6f1f06a579dab4e01fd8df65f2ff1.jpg', '', '0', '0', '0', '', '', '1567738617', '1567776050');
INSERT INTO `pm_goods_cate` VALUES ('24', '11', '简约优雅 ', '', '50', '0-1-11-24-', '/uploads/images/20190906/67508efa4a5b868f0283759582c1732b.jpg', '', '0', '0', '0', '', '', '1567738706', '1567775697');
INSERT INTO `pm_goods_cate` VALUES ('25', '11', '奢华高贵', '', '50', '0-1-11-25-', '/uploads/images/20190906/940da6f12615dcc78a02d2350e24a829.jpg', '', '0', '0', '0', '', '', '1567738722', '1567775812');
INSERT INTO `pm_goods_cate` VALUES ('26', '12', '无钢圈', '', '50', '0-1-12-26-', '/uploads/images/20190906/7d0b9b07507a7de6b8ec6750a92cd2ed.jpg', '', '0', '0', '0', '', '', '1567738740', '1567776117');
INSERT INTO `pm_goods_cate` VALUES ('27', '13', '船袜', '', '50', '0-1-13-27-', '/uploads/images/20190906/1a2f7e3a483217e16f1274fe0731ff9a.jpg', '', '0', '0', '0', '', '', '1567738771', '1567776254');
INSERT INTO `pm_goods_cate` VALUES ('28', '13', '连裤袜', '', '50', '0-1-13-28-', '/uploads/images/20190906/50cc1f012b71525bc7c2e64815d1c82b.jpg', '', '0', '0', '0', '', '', '1567738783', '1567776385');
INSERT INTO `pm_goods_cate` VALUES ('29', '13', '隐形袜', '', '50', '0-1-13-29-', '/uploads/images/20190906/7fc59808c3750f87b5a255433a6b8a4c.jpg', '', '0', '0', '0', '', '', '1567738811', '1567776452');
INSERT INTO `pm_goods_cate` VALUES ('30', '0', '箱包', '', '50', '0-30-', '/uploads/images/20190906/a7f6b8ae6eb8cf07eec518d2ce891bbe.jpg', '', '0', '1', '0', '', '', '1567740140', '1567740611');
INSERT INTO `pm_goods_cate` VALUES ('31', '4', '婴儿奶粉', '', '1', '0-4-31-', '', '', '0', '0', '0', '', '', '1568127506', '1568953837');
INSERT INTO `pm_goods_cate` VALUES ('32', '31', 'A2铂金', '', '50', '0-4-31-32-', '/uploads/images/20190920/f464dcc4d4f1cf02aa19c01076c04282.jpg', '', '0', '0', '0', '', '', '1568127625', '1568949838');
INSERT INTO `pm_goods_cate` VALUES ('36', '31', '爱他美金装', '', '50', '0-4-31-36-', '/uploads/images/20190920/15f31dc7fad1f7a9b4813ba41cdda256.jpg', '', '0', '0', '0', '', '', '1568949930', '1568949930');
INSERT INTO `pm_goods_cate` VALUES ('55', '31', '水解奶粉', '', '50', '0-4-31-55-', '/uploads/images/20190920/ff1699d389973eff787250508273f842.jpg', '', '0', '0', '0', '', '', '1568953014', '1568953014');
INSERT INTO `pm_goods_cate` VALUES ('45', '34', '孕妇奶粉', '', '50', '0-4-34-45-', '/uploads/images/20190920/a0b519a27749e0f7450d7ba236831705.jpg', '', '0', '0', '0', '', '', '1568952013', '1568952013');
INSERT INTO `pm_goods_cate` VALUES ('34', '4', '成人奶粉', '', '50', '0-4-34-', '', '', '0', '0', '0', '', '', '1568949756', '1568949756');
INSERT INTO `pm_goods_cate` VALUES ('37', '31', '爱他美铂金', '', '50', '0-4-31-37-', '/uploads/images/20190920/5f07bccaa314816a274375bc17282111.png', '', '0', '0', '0', '', '', '1568950212', '1568951689');
INSERT INTO `pm_goods_cate` VALUES ('38', '31', '贝拉米', '', '50', '0-4-31-38-', '/uploads/images/20190920/25d6a132ddc7852036d050a8af4fa3c8.jpg', '', '0', '0', '0', '', '', '1568950564', '1568951705');
INSERT INTO `pm_goods_cate` VALUES ('39', '4', '孕妈必备', '', '50', '0-4-39-', '', '', '0', '0', '0', '', '', '1568950739', '1568952659');
INSERT INTO `pm_goods_cate` VALUES ('40', '31', '可瑞康羊奶', '', '50', '0-4-31-40-', '/uploads/images/20190920/08764659c84c2a61d0cf73fc1ad0e3d3.jpg', '', '0', '0', '0', '', '', '1568951115', '1568951723');
INSERT INTO `pm_goods_cate` VALUES ('41', '31', '惠氏金装S26', '', '50', '0-4-31-41-', '/uploads/images/20190920/ab13373d8cc4027c9bce417ef4356813.png', '', '0', '0', '0', '', '', '1568951278', '1568951743');
INSERT INTO `pm_goods_cate` VALUES ('42', '31', '满趣健草饲', '', '50', '0-4-31-42-', '/uploads/images/20190920/5cf75dc5322bcdb5d1037658cd2fea7e.jpg', '', '0', '0', '0', '', '', '1568951395', '1568951395');
INSERT INTO `pm_goods_cate` VALUES ('47', '34', '雅培小安素', '', '50', '0-4-34-47-', '/uploads/images/20190920/ef60653c04bf1a8b4cf2a0f77bff1b85.png', '', '0', '0', '0', '', '', '1568952113', '1568952113');
INSERT INTO `pm_goods_cate` VALUES ('46', '34', '德运奶粉', '', '50', '0-4-34-46-', '/uploads/images/20190920/7b13705c5d582df183071d4a3ae48499.jpg', '', '0', '0', '0', '', '', '1568952047', '1568952047');
INSERT INTO `pm_goods_cate` VALUES ('48', '34', '美可卓', '', '50', '0-4-34-48-', '/uploads/images/20190920/6619be2c80ca7b54d0dc38fccc076e22.jpg', '', '0', '0', '0', '', '', '1568952190', '1568952190');
INSERT INTO `pm_goods_cate` VALUES ('49', '34', '老年奶粉', '', '50', '0-4-34-49-', '/uploads/images/20190920/3cb0994f7d08005efceb1ac8b8a7c619.jpg', '', '0', '0', '0', '', '', '1568952253', '1568952253');
INSERT INTO `pm_goods_cate` VALUES ('50', '34', '羊奶粉', '', '50', '0-4-34-50-', '/uploads/images/20190920/e9e417b1a4557c921c0674655bf8c439.jpg', '', '0', '0', '0', '', '', '1568952340', '1568952340');
INSERT INTO `pm_goods_cate` VALUES ('51', '31', 'Bubs羊奶', '', '50', '0-4-31-51-', '/uploads/images/20190920/0c8d880430486b541d41d331a0b09a53.jpg', '', '0', '0', '0', '', '', '1568952403', '1568952403');
INSERT INTO `pm_goods_cate` VALUES ('56', '4', '婴儿必备', '', '3', '0-4-56-', '', '', '0', '0', '0', '', '', '1568953825', '1568953875');
INSERT INTO `pm_goods_cate` VALUES ('53', '39', '孕妈护肤', '', '50', '0-4-39-53-', '/uploads/images/20190920/4f7540f07c73ab6fd4ccde638b3ef452.jpg', '', '0', '0', '0', '', '', '1568952889', '1568952889');
INSERT INTO `pm_goods_cate` VALUES ('54', '39', '孕妈保健', '', '50', '0-4-39-54-', '/uploads/images/20190920/04d419c9c3da37169bf71c971024b1d4.jpg', '', '0', '0', '0', '', '', '1568952923', '1568952923');
INSERT INTO `pm_goods_cate` VALUES ('57', '56', '营养保健', '', '50', '0-4-56-57-', '/uploads/images/20190920/f4ff006d377e98c14759311e36f0fc62.jpg', '', '0', '0', '0', '', '', '1568953947', '1568954373');
INSERT INTO `pm_goods_cate` VALUES ('58', '56', '婴儿辅食', '', '50', '0-4-56-58-', '/uploads/images/20190920/74dd406a2f9d93eb1f42caf022d988ed.png', '', '0', '0', '0', '', '', '1568954163', '1568954163');
INSERT INTO `pm_goods_cate` VALUES ('59', '56', '维生素', '', '50', '0-4-56-59-', '/uploads/images/20190920/0b092ffeffcfde47b8b5b6808e0116d5.jpg', '', '0', '0', '0', '', '', '1568954205', '1568954351');
INSERT INTO `pm_goods_cate` VALUES ('60', '56', '碗&杯子', '', '50', '0-4-56-60-', '/uploads/images/20190920/92b5ca112cc313ef945b3680353c5562.jpg', '', '0', '0', '0', '', '', '1568954267', '1568954325');
INSERT INTO `pm_goods_cate` VALUES ('61', '56', '护肤防晒', '', '50', '0-4-56-61-', '/uploads/images/20190920/c8198fe93feff00845b582b982ef77c5.jpg', '', '0', '0', '0', '', '', '1568954405', '1568954405');
INSERT INTO `pm_goods_cate` VALUES ('62', '56', '牙膏牙刷', '', '50', '0-4-56-62-', '/uploads/images/20190920/588481535106be73e870730393b7feb7.jpg', '', '0', '0', '0', '', '', '1568954433', '1568954580');
INSERT INTO `pm_goods_cate` VALUES ('63', '56', '日用洗护', '', '50', '0-4-56-63-', '/uploads/images/20190920/f5367ae776104816e8cb12faf4ae3771.png', '', '0', '0', '0', '', '', '1568954505', '1568954505');
INSERT INTO `pm_goods_cate` VALUES ('64', '7', '皮肤美妆', '', '50', '0-7-64-', '', '', '0', '0', '0', '', '', '1568958372', '1568958501');
INSERT INTO `pm_goods_cate` VALUES ('66', '7', '爽肤润肤', '', '50', '0-7-66-', '', '', '0', '0', '0', '', '', '1568958536', '1568958536');
INSERT INTO `pm_goods_cate` VALUES ('65', '7', '洁面卸妆', '', '50', '0-7-65-', '', '', '0', '0', '0', '', '', '1568958518', '1568958518');
INSERT INTO `pm_goods_cate` VALUES ('67', '7', '面霜精华', '', '1', '0-7-67-', '', '', '0', '0', '0', '', '', '1568958571', '1568958650');
INSERT INTO `pm_goods_cate` VALUES ('68', '7', '眼霜精华', '', '50', '0-7-68-', '', '', '0', '0', '0', '', '', '1568958599', '1568958599');
INSERT INTO `pm_goods_cate` VALUES ('69', '67', '美白面膜', '', '50', '0-7-67-69-', '/uploads/images/20190920/831986b229f5143fb11e3be4ec282ac8.png', '', '0', '0', '0', '', '', '1568958674', '1568958674');
INSERT INTO `pm_goods_cate` VALUES ('70', '64', '素颜霜', '', '50', '0-7-64-70-', '/uploads/images/20190920/29b72782b8ac2d10885e686b9c41fd60.png', '', '0', '0', '0', '', '', '1568958687', '1568958709');
INSERT INTO `pm_goods_cate` VALUES ('71', '67', '水光针', '', '50', '0-7-67-71-', '/uploads/images/20190920/ade06466b409d5a06cbbd1371bd68912.jpg', '', '0', '0', '0', '', '', '1568958745', '1568958745');
INSERT INTO `pm_goods_cate` VALUES ('72', '66', '磨砂膏', '', '50', '0-7-66-72-', '/uploads/images/20190920/9faebbbf65fc8a05642a7e34193e684b.jpg', '', '0', '0', '0', '', '', '1568958797', '1568958797');
INSERT INTO `pm_goods_cate` VALUES ('73', '66', '水光乳', '', '50', '0-7-66-73-', '/uploads/images/20190920/3fd940ae2d493fbcd2f4384a708728a8.jpg', '', '0', '0', '0', '', '', '1568958837', '1568958837');
INSERT INTO `pm_goods_cate` VALUES ('74', '67', '蜂胶面膜', '', '50', '0-7-67-74-', '/uploads/images/20190920/c38baeab1fdd71723436e808ca353a2e.jpg', '', '0', '0', '0', '', '', '1568958869', '1568958869');
INSERT INTO `pm_goods_cate` VALUES ('75', '67', '淡斑精华', '', '50', '0-7-67-75-', '/uploads/images/20190920/846672725666844b4afab9f0f7645bca.jpg', '', '0', '0', '0', '', '', '1568958906', '1568958906');
INSERT INTO `pm_goods_cate` VALUES ('76', '64', '水光霜', '', '50', '0-7-64-76-', '/uploads/images/20190920/3d1e55c42f3045a84490b55c66ced188.jpg', '', '0', '0', '0', '', '', '1568958933', '1568958933');
INSERT INTO `pm_goods_cate` VALUES ('77', '65', '爽肤水', '', '50', '0-7-65-77-', '/uploads/images/20190920/7a6ef0dcdd46c3a2a0ce7462e9789e8d.png', '', '0', '0', '0', '', '', '1568958986', '1568958986');
INSERT INTO `pm_goods_cate` VALUES ('78', '65', '卸妆膏', '', '50', '0-7-65-78-', '/uploads/images/20190920/1a611eb504598591639afd8c212a4777.png', '', '0', '0', '0', '', '', '1568959019', '1568959019');
INSERT INTO `pm_goods_cate` VALUES ('79', '65', '卸妆水', '', '50', '0-7-65-79-', '/uploads/images/20190920/4706e2e8623f45d50c24e521af053855.jpg', '', '0', '0', '0', '', '', '1568959043', '1568959043');
INSERT INTO `pm_goods_cate` VALUES ('80', '68', '眼膜', '', '50', '0-7-68-80-', '/uploads/images/20190920/edc6c072683a690d64891b57492d5885.jpg', '', '0', '0', '0', '', '', '1568959144', '1568959144');
INSERT INTO `pm_goods_cate` VALUES ('81', '68', '去皱', '', '50', '0-7-68-81-', '/uploads/images/20190920/466a19234e1fb1630266408035e2a3e5.png', '', '0', '0', '0', '', '', '1568959174', '1568959174');
INSERT INTO `pm_goods_cate` VALUES ('82', '68', '蜂毒眼霜', '', '50', '0-7-68-82-', '/uploads/images/20190920/f9fe1bd1b05680d985751a89883b1c71.jpg', '', '0', '0', '0', '', '', '1568959204', '1568959204');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_goods_push
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

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
INSERT INTO `pm_login_log` VALUES ('21', '0', '10001', 'oUr_q1V4pRzpSCWDHKigfAS0L_CU', '1569980154', '58.170.214.132');
INSERT INTO `pm_login_log` VALUES ('22', '0', '10003', 'oUr_q1S3hcUvKPEFeyvYMRnSVNPg', '1570429737', '115.64.186.23');
INSERT INTO `pm_login_log` VALUES ('23', '0', '10002', 'oUr_q1Q9i-YuVGt6kVh-ZF2IFTBg', '1570439929', '182.126.255.136');
INSERT INTO `pm_login_log` VALUES ('24', '0', '10004', 'oUr_q1RsF47aHa0zVUPV4EPTL1pg', '1570443898', '144.134.132.7');
INSERT INTO `pm_login_log` VALUES ('25', '0', '10001', 'oUr_q1V4pRzpSCWDHKigfAS0L_CU', '1570448007', '58.170.214.132');
INSERT INTO `pm_login_log` VALUES ('26', '0', '10001', 'oUr_q1V4pRzpSCWDHKigfAS0L_CU', '1570451481', '58.170.214.132');
INSERT INTO `pm_login_log` VALUES ('27', '0', '10002', 'oUr_q1Q9i-YuVGt6kVh-ZF2IFTBg', '1570464087', '182.126.255.136');
INSERT INTO `pm_login_log` VALUES ('28', '0', '10003', 'oUr_q1S3hcUvKPEFeyvYMRnSVNPg', '1570499817', '120.20.196.235');
INSERT INTO `pm_login_log` VALUES ('29', '0', '10002', 'oUr_q1Q9i-YuVGt6kVh-ZF2IFTBg', '1570524617', '123.55.157.36');
INSERT INTO `pm_login_log` VALUES ('30', '0', '10003', 'oUr_q1S3hcUvKPEFeyvYMRnSVNPg', '1570700670', '115.64.186.23');
INSERT INTO `pm_login_log` VALUES ('31', '0', '10003', 'oUr_q1S3hcUvKPEFeyvYMRnSVNPg', '1570922804', '120.20.133.82');
INSERT INTO `pm_login_log` VALUES ('32', '0', '10004', 'oUr_q1RsF47aHa0zVUPV4EPTL1pg', '1571023552', '49.182.32.144');

-- ----------------------------
-- Table structure for `pm_member`
-- ----------------------------
DROP TABLE IF EXISTS `pm_member`;
CREATE TABLE `pm_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录密码',
  `nickname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tjID` int(11) NOT NULL,
  `tjName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wechat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `headimg` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team` tinyint(4) NOT NULL,
  `group` tinyint(4) NOT NULL COMMENT '0普通会员1特惠用户',
  `disable` tinyint(4) NOT NULL COMMENT '0正常 1禁用',
  `token` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_out` int(11) NOT NULL,
  `createTime` int(11) NOT NULL COMMENT '注册时间',
  `createIP` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '注册IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10014 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pm_member
-- ----------------------------
INSERT INTO `pm_member` VALUES ('10001', 'oUr_q1V4pRzpSCWDHKigfAS0L_CU', '', '', '?化魄?', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoqxXeMKjUfuM54IwTkNhc5Nxc3x0W1a7z6MOhEibbXJm1dhsOibictNDL4DPMxhFVT8DzMJaaHs4hvg/132', '1', '1', '0', '5d47c483c75b3d04c47ec20c7e1c9639d58aa3d6', '1573431439', '1569978589', '58.170.214.132');
INSERT INTO `pm_member` VALUES ('10002', 'oUr_q1Q9i-YuVGt6kVh-ZF2IFTBg', '', '', '東', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoNiaxt1lWicmdJH7P08FO3uJ4EfBT7tyM438PXcKFgnibyz8s80emzmoMvGibK0EjbgpcaYPiad4eY6ibg/132', '0', '0', '0', '07071b35024005c58868a362ed56c2e9c4a4d591', '1574501623', '1569980152', '182.126.255.169');
INSERT INTO `pm_member` VALUES ('10003', 'oUr_q1S3hcUvKPEFeyvYMRnSVNPg', '', '', 'Eric Yao', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/siacArmwCKLHbxD7ZfF2PTZSdlz7gpMgfnlJ9YwtPRro9gFX0H1fJUfHYpvBX5xqoAWRhVAHFLyiccVzrhfTzErw/132', '0', '0', '0', 'eb66fe3ea9fa93114b63463313992864ed396512', '1573514805', '1569987075', '120.20.3.2');
INSERT INTO `pm_member` VALUES ('10004', 'oUr_q1RsF47aHa0zVUPV4EPTL1pg', '', '', 'mikel??⁷⁰', '10003', 'Eric Yao', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/iaPp7ClZOGcpiblZK94zy1EM2Dy0BNe1icC9OqCfDWelmflWuMFiblbTrTvZEMQnclfsfZY4etsic57QkCOhLSQZUicg/132', '0', '0', '0', '8123a1675085dd0c503feedc0f7bdd4c790ea15c', '1573615553', '1569990133', '144.134.132.7');
INSERT INTO `pm_member` VALUES ('10005', 'oUr_q1bodoBtTAG_leTp4X7ONyHs', '', '', '月明', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/4oYLkYu9riaGiaOX2NmvuibuSQHXQ3f5FFBcT9236wiaZIg2BYVGAn26HdXJk4icypRFCQs5qelDsgIZ44jYCj0VwhQ/132', '0', '0', '0', 'c4f5bee8d00c58aad482faa550bebe71035547b3', '1573218768', '1570346469', '182.126.252.224');
INSERT INTO `pm_member` VALUES ('10006', 'oUr_q1RcAz6qcWK_wxh5Zr6lK4_8', '', '', '阿德眼代发平台客服', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/t8Eck8uyYXMR7NMel5zkyrcG0Hx3TjSfsGhxQpaePibDnACPeqvHV9YUPV2JbwKJKPiahkS4Im5e0dw0j6RUaesA/132', '0', '0', '0', 'b22c6abec7097456d8f163ef47fb0b9b1f318e6e', '1573033907', '1570441894', '58.170.214.132');
INSERT INTO `pm_member` VALUES ('10007', 'oUr_q1UR7hzqCPOyA_ZPwvL2Wl9U', '', '', 'A安尔捷澳洲奶粉保健品批发', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/OLRT4AiawpTzc6cKnicIss79VS93fEXGPqnSLjBJAGPibYc0hYX897MQ5xrk8lbiaPKOA4FJBQEicjsly2DyHgePyqw/132', '0', '0', '0', '09ff5afdc682d2273845037146986cc1d6a3d2d8', '1573033959', '1570441951', '58.170.214.132');
INSERT INTO `pm_member` VALUES ('10008', 'oUr_q1ZBCN-mudSs0SF3u1EJWeiM', '', '', 'Aaron陈仲杰', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIGGBhiaUAJpeP6VOW0syluusL35Lgia34LSo22LvGSXvFwQ89gOU13wWDAqNiaSaxCD5jicibA53dOhog/132', '0', '0', '0', '55a45a16b5c940cadce8080f32939cbc5f581b35', '1573039365', '1570447342', '116.240.154.23');
INSERT INTO `pm_member` VALUES ('10009', 'oUr_q1eR5z0jiBf7JZjRePHILlyg', '', '', 'Sumanation?', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIpHamcOVKrbibtdMZx0u4u7VrQqB0DlyUQyOZKUgoiauamuFdPg6k1BA9BbpvCrnmiav2GZjic2QDlzQ/132', '0', '0', '0', 'd2c7ffcfdec7ac458cd80f3065e2efd0180822ec', '1573040021', '1570447940', '144.134.132.7');
INSERT INTO `pm_member` VALUES ('10010', 'oUr_q1Ryz9HVJxcBzjD9ypx1AW_s', '', '', '苍耳', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM64hgXicLica5nuEP3gnFz8LtSgMbIzUUju37ialatkriapwdVwAAoElk0Bq8TlIeHgxwQ3ic1ks1C9HPQ/132', '0', '0', '0', '7ad63b0ec3b7ed983fec0edc36195a8d982b2d33', '1573043616', '1570451478', '121.228.175.155');
INSERT INTO `pm_member` VALUES ('10011', 'oUr_q1TlqA-NA_8aEjzx5hKSI74c', '', '', '摴醴訾', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/TtbLZL3QB99AfRwLRqbZVtsl9UqZAzCO8MjhKpy5MR9JSHAqgZwFicDiaD0WDT9NLv8CmQcPPyOog955ibgDwOAAg/132', '0', '0', '0', '87b84a1ec4b4f63b7cd5d1675732c1716f84a1fe', '1573085045', '1570493023', '122.4.232.183');
INSERT INTO `pm_member` VALUES ('10012', 'oUr_q1bMKOSvqea7DHjePBjqHlco', '', '', 'Cindy ^_^', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/pPggnOl3v1UUPsU86p1uGOP6ZK5VhbAKXUVoz0mu1U8fuHEUovicAMycib0V8AnfcjXKlSWgic6pPxBkArMZRjYrg/132', '0', '0', '0', 'af036c3d3640c94ff0f01341ca1f7b522844ca4c', '1573132007', '1570539577', '203.220.153.168');
INSERT INTO `pm_member` VALUES ('10013', 'oUr_q1eyBTBkid2DJXIVGMjIJIis', '', '', 'eleven-nono', '0', '', '', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83epJtx5b1JDVuTia45vTW9sXrgN6pT1k0dwhB6pf8bHsd1RSxiaYMJjelDw9BxyuYSDAawjekv8Nia6dw/132', '0', '0', '0', '887ceccdcee2d720770160f6c5556ede1bcc71cd', '1573467679', '1570875679', '114.242.249.236');

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
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

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
INSERT INTO `pm_node` VALUES ('90', '直邮包裹', 'baoguo', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('91', '基金返利', 'fund', '1', '', '73', '2', '', '0', '50', '0');
INSERT INTO `pm_node` VALUES ('92', '财务明细', 'finance', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('93', '待支付', 'order/nopay', '1', '', '83', '3', '', '0', '10', '1');
INSERT INTO `pm_node` VALUES ('94', '商家管理', '', '1', '', '0', '1', 'layui-icon-group', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('95', '商家列表', 'shop', '1', '', '94', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('96', '城市管理', 'city', '1', '', '94', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('97', '快递管理', 'express', '1', '', '94', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('98', '自提包裹', 'ziti', '1', '', '73', '2', '', '0', '50', '1');
INSERT INTO `pm_node` VALUES ('99', '销售报表', 'report', '1', '', '73', '2', '', '0', '50', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_option_cate
-- ----------------------------
INSERT INTO `pm_option_cate` VALUES ('5', '商家分类', '', '50', '1567757261', '1567757261');
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_option_item
-- ----------------------------
INSERT INTO `pm_option_item` VALUES ('25', '5', '母婴专区', '', '', '50', 'M', '', '1568956419', '1568956419');
INSERT INTO `pm_option_item` VALUES ('26', '5', '美妆护肤', '', '', '50', 'M', '', '1568956432', '1568956432');
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
INSERT INTO `pm_option_item` VALUES ('22', '3', '阿德', '', '', '50', 'A', '', '1568284563', '1568284563');
INSERT INTO `pm_option_item` VALUES ('23', '3', '悉尼', '', '', '50', 'X', '', '1568284574', '1568284574');
INSERT INTO `pm_option_item` VALUES ('24', '3', '墨尔本', '', '', '50', 'M', '', '1568284581', '1568284581');
INSERT INTO `pm_option_item` VALUES ('27', '5', '天然保健', '', '', '50', 'T', '', '1568956450', '1568956465');
INSERT INTO `pm_option_item` VALUES ('28', '5', '纯净蜂蜜', '', '', '50', 'C', '', '1568956475', '1568956475');
INSERT INTO `pm_option_item` VALUES ('29', '3', '塔斯马尼亚', '', '', '50', 'T', '', '1570445667', '1570445667');

-- ----------------------------
-- Table structure for `pm_order`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order`;
CREATE TABLE `pm_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
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
  `quhuoType` tinyint(4) NOT NULL COMMENT '0邮寄 1到店取货',
  `payType` tinyint(11) NOT NULL COMMENT '1omi支付 2余额支付',
  `payStatus` tinyint(11) NOT NULL COMMENT '0未支付 1已支付',
  `status` tinyint(4) NOT NULL COMMENT '0待支付 1待发货 2已发货(待收货) 3待评价 99交易关闭',
  `hide` tinyint(4) NOT NULL,
  `comment` tinyint(4) NOT NULL,
  `send` tinyint(4) NOT NULL,
  `cancel` tinyint(4) NOT NULL COMMENT '取消订单',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order
-- ----------------------------
INSERT INTO `pm_order` VALUES ('1', '9', '10001', '0', '19100211145182', '22.20', '0', '0', '18.00', '0', '0.00', '0.00', '24.25', '4.20', '1', '赵云', '18523651112', '', '', '', '河南省', '开封市', '龙亭区', '中山路435号', '水光针专卖', '0410867533', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569986091', '0');
INSERT INTO `pm_order` VALUES ('2', '7', '10001', '0', '19100211160012', '18.00', '0', '0', '18.00', '0', '0.00', '0.00', '28.50', '0.00', '1', '赵云', '18523651112', '', '', '', '河南省', '开封市', '龙亭区', '中山路435号', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569986160', '0');
INSERT INTO `pm_order` VALUES ('3', '9', '10001', '0', '19100211370144', '22.20', '0', '0', '18.00', '0', '0.00', '0.00', '23.75', '4.20', '1', '赵云', '18523651112', '', '', '', '河南省', '开封市', '龙亭区', '中山路435号', '水光针专卖', '0410867533', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987421', '0');
INSERT INTO `pm_order` VALUES ('4', '8', '10003', '0', '19100211385355', '117.00', '297', '60', '117.00', '0', '0.00', '0.00', '232.80', '0.00', '4', 'A', '13309877658', '', '', '', '北京市', '北京市', '东城区', 'Gajj', 'Chemist Warehouse', '18700001111', '#', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987533', '0');
INSERT INTO `pm_order` VALUES ('5', '9', '10003', '0', '19100211385381', '22.20', '0', '0', '18.00', '0', '0.00', '0.00', '23.75', '4.20', '4', 'A', '13309877658', '', '', '', '北京市', '北京市', '东城区', 'Gajj', '水光针专卖', '0410867533', '#', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987533', '0');
INSERT INTO `pm_order` VALUES ('6', '8', '10001', '0', '19100211402368', '39.00', '99', '20', '39.00', '0', '0.00', '0.00', '77.60', '0.00', '1', '赵云', '18523651112', '', '', '', '河南省', '开封市', '龙亭区', '中山路435号', 'Chemist Warehouse', '18700001111', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987623', '0');
INSERT INTO `pm_order` VALUES ('7', '10', '10003', '0', '19100211420396', '15.60', '0', '0', '12.00', '0', '0.00', '0.00', '38.00', '3.60', '4', 'A', '13309877658', '', '', '', '北京市', '北京市', '东城区', 'Gajj', 'Natio 系列专卖', '0410867533', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987723', '0');
INSERT INTO `pm_order` VALUES ('18', '7', '10002', '0', '19100714110210', '38.80', '0', '0', '34.00', '0', '0.00', '0.00', '27.60', '4.80', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1570428662', '0');
INSERT INTO `pm_order` VALUES ('9', '10', '10003', '0', '19100211434752', '3.61', '0', '0', '0.01', '0', '0.00', '0.00', '38.00', '3.60', '4', 'A', '13309877658', '', '', '', '北京市', '北京市', '东城区', 'Gajj', 'Natio 系列专卖', '0410867533', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1569987827', '0');
INSERT INTO `pm_order` VALUES ('10', '7', '10003', '0', '19100215112525', '18.00', '0', '0', '18.00', '0', '0.00', '0.00', '28.50', '0.00', '4', 'A', '13309877658', '', '', '', '北京市', '北京市', '东城区', 'Gajj', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1570000285', '0');
INSERT INTO `pm_order` VALUES ('11', '9', '10002', '0', '19100215431441', '22.20', '0', '0', '18.00', '0', '0.00', '0.00', '24.25', '4.20', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', '水光针专卖', '0410867533', '', null, '0', '0', '0', '1', '1', '0', '0', '0', '0', '1570002194', '1570003311');
INSERT INTO `pm_order` VALUES ('12', '8', '10002', '0', '19100216210124', '70.00', '79', '20', '70.00', '0', '0.00', '0.00', '58.20', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '', null, '0', '0', '0', '1', '1', '0', '0', '0', '0', '1570004461', '1570004488');
INSERT INTO `pm_order` VALUES ('13', '8', '10002', '0', '19100216220255', '70.00', '79', '20', '70.00', '0', '0.00', '0.00', '58.20', '0.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '', null, '0', '0', '0', '1', '1', '0', '0', '0', '0', '1570004522', '1570004558');
INSERT INTO `pm_order` VALUES ('14', '7', '10002', '0', '19100216372871', '38.80', '0', '0', '34.00', '0', '0.00', '0.00', '27.60', '4.80', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '1', '1', '0', '0', '0', '0', '1570005448', '1570005465');
INSERT INTO `pm_order` VALUES ('15', '7', '10001', '0', '19100221554454', '18.00', '0', '0', '18.00', '0', '0.00', '0.00', '28.50', '0.00', '5', '测试', '13206485795', '', '', '', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1570024544', '0');
INSERT INTO `pm_order` VALUES ('16', '8', '10001', '0', '19100308322685', '23.72', '24', '10', '22.00', '0', '0.00', '0.00', '18.05', '1.72', '5', '测试', '13206485795', '', '', '', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', 'Chemist Warehouse', '18700001111', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1570062746', '0');
INSERT INTO `pm_order` VALUES ('19', '9', '10001', '0', '19100918105348', '22.20', '0', '0', '18.00', '0', '0.00', '0.00', '24.25', '4.20', '5', '测试', '13206485795', '', '', '', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', '水光针专卖', '0410867533', '', null, '0', '1', '0', '0', '0', '0', '0', '0', '0', '1570615853', '0');
INSERT INTO `pm_order` VALUES ('20', '9', '10001', '0', '19101023225516', '24.20', '0', '0', '20.00', '0', '0.00', '0.00', '24.25', '4.20', '5', '测试', '13206485795', '', '', '', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', '水光针专卖', '0410867533', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1570720975', '0');
INSERT INTO `pm_order` VALUES ('21', '7', '10002', '0', '19102415544390', '103.30', '19', '5', '89.00', '0', '0.00', '0.00', '70.65', '14.30', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1571903683', '0');
INSERT INTO `pm_order` VALUES ('22', '7', '10002', '0', '19102415572947', '103.30', '19', '5', '89.00', '0', '0.00', '0.00', '70.65', '14.30', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '#', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order` VALUES ('23', '8', '10002', '0', '19102415572943', '330.00', '304', '90', '304.00', '0', '0.00', '0.00', '219.05', '26.00', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '#', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order` VALUES ('24', '7', '10002', '0', '19102417274647', '2497.50', '0', '0', '2000.00', '0', '0.00', '0.00', '3277.50', '497.50', '2', '张明', '13500000000', '2222222', 'http://127.0.0.10/uploads/sn/10002/OJNDAUC5hJtJkWzl.png', '/uploads/sn/10002/UUW5WT0rAS08RIVq.png', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '', null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '1571909266', '0');

-- ----------------------------
-- Table structure for `pm_order_baoguo`
-- ----------------------------
DROP TABLE IF EXISTS `pm_order_baoguo`;
CREATE TABLE `pm_order_baoguo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL COMMENT '用户订单号',
  `memberID` int(11) NOT NULL,
  `order_no` varchar(50) NOT NULL COMMENT '商家订单号',
  `type` tinyint(4) NOT NULL COMMENT '1，2奶粉类 7鞋子，剩余其他类',
  `payment` decimal(8,2) NOT NULL COMMENT '物流费',
  `wuliuInprice` decimal(8,2) NOT NULL,
  `weight` decimal(8,2) NOT NULL COMMENT '总价格',
  `express` varchar(50) NOT NULL COMMENT '物流公司',
  `expressID` int(11) NOT NULL,
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
  `hexiao` tinyint(4) NOT NULL,
  `cancel` tinyint(4) NOT NULL COMMENT '取消订单',
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_baoguo
-- ----------------------------
INSERT INTO `pm_order_baoguo` VALUES ('1', '9', '1', '10001', '19100211145182', '9', '4.20', '1.68', '0.30', '中环($6/kg)', '0', '', '', '', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', '水光针专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1569986091', '0');
INSERT INTO `pm_order_baoguo` VALUES ('2', '7', '2', '10001', '19100211160012', '1', '0.00', '3.85', '1.10', '澳邮', '0', '', '', '', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1569986160', '0');
INSERT INTO `pm_order_baoguo` VALUES ('3', '9', '3', '10001', '19100211370144', '10', '4.20', '1.68', '0.30', '中环($6/kg)', '0', '', '', '', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', '水光针专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1569987421', '0');
INSERT INTO `pm_order_baoguo` VALUES ('4', '8', '4', '10003', '19100211385355', '1', '0.00', '17.85', '5.10', '澳邮', '0', '', '', '', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1569987533', '0');
INSERT INTO `pm_order_baoguo` VALUES ('5', '9', '5', '10003', '19100211385381', '10', '4.20', '1.68', '0.30', '中环($6/kg)', '0', '', '', '', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', '水光针专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1569987533', '0');
INSERT INTO `pm_order_baoguo` VALUES ('6', '8', '6', '10001', '19100211402368', '1', '0.00', '5.95', '1.70', '澳邮', '0', '', '', '', '赵云', '18523651112', '河南省', '开封市', '龙亭区', '中山路435号', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1569987623', '0');
INSERT INTO `pm_order_baoguo` VALUES ('7', '10', '7', '10003', '19100211420396', '9', '3.60', '2.24', '0.40', '中环($6/kg)', '0', '', '', '', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', 'Natio 系列专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1569987723', '0');
INSERT INTO `pm_order_baoguo` VALUES ('9', '10', '9', '10003', '19100211434752', '9', '3.60', '2.24', '0.40', '中环($6/kg)', '0', '', '', '', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', 'Natio 系列专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1569987827', '0');
INSERT INTO `pm_order_baoguo` VALUES ('10', '7', '10', '10003', '19100215112525', '1', '0.00', '3.85', '1.10', '澳邮', '0', '', '', '', 'A', '13309877658', '北京市', '北京市', '东城区', 'Gajj', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1570000285', '0');
INSERT INTO `pm_order_baoguo` VALUES ('11', '9', '11', '10002', '19100215431441', '9', '4.20', '1.68', '0.30', '中环($6/kg)', '0', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', '水光针专卖', '0410867533', '1', '0', '0', '0', '0', '0', '1570002194', '0');
INSERT INTO `pm_order_baoguo` VALUES ('12', '8', '12', '10002', '19100216210124', '1', '0.00', '3.85', '1.10', '澳邮', '0', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '1', '0', '0', '0', '0', '0', '1570004461', '0');
INSERT INTO `pm_order_baoguo` VALUES ('13', '8', '13', '10002', '19100216220255', '1', '0.00', '3.85', '1.10', '澳邮', '0', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '1', '0', '0', '0', '0', '0', '1570004522', '0');
INSERT INTO `pm_order_baoguo` VALUES ('14', '7', '14', '10002', '19100216372871', '4', '4.80', '1.12', '0.20', '中环($6/kg)', '0', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '1', '0', '0', '0', '0', '0', '1570005448', '0');
INSERT INTO `pm_order_baoguo` VALUES ('15', '7', '15', '10001', '19100221554454', '1', '0.00', '3.85', '1.10', '澳邮', '0', '', '', '', '测试', '13206485795', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1570024544', '0');
INSERT INTO `pm_order_baoguo` VALUES ('16', '8', '16', '10001', '19100308322685', '1', '1.72', '2.10', '0.60', '澳邮', '0', '', '', '', '测试', '13206485795', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1570062746', '0');
INSERT INTO `pm_order_baoguo` VALUES ('18', '7', '18', '10002', '19100714110210', '4', '4.80', '1.12', '0.20', '中环($6/kg)', '0', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1570428662', '0');
INSERT INTO `pm_order_baoguo` VALUES ('19', '9', '20', '10001', '19101023225516', '9', '4.20', '1.68', '0.30', '中环($6/kg)', '0', '', '', '', '测试', '13206485795', '吉林省', '松原市', '宁江区', '除非沟沟壑壑', '水光针专卖', '0410867533', '0', '0', '0', '0', '0', '0', '1570720975', '0');
INSERT INTO `pm_order_baoguo` VALUES ('20', '7', '22', '10002', '19102415572947', '5', '14.30', '0.00', '2.20', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order_baoguo` VALUES ('21', '7', '22', '10002', '19102415572947', '7', '0.00', '0.00', '1.00', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order_baoguo` VALUES ('22', '8', '23', '10002', '19102415572943', '1', '13.00', '0.00', '2.20', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order_baoguo` VALUES ('23', '8', '23', '10002', '19102415572943', '2', '13.00', '0.00', '2.20', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order_baoguo` VALUES ('24', '8', '23', '10002', '19102415572943', '3', '0.00', '0.00', '1.00', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1571903849', '0');
INSERT INTO `pm_order_baoguo` VALUES ('25', '8', '23', '10002', '19102415572943', '3', '0.00', '0.00', '1.00', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1571903850', '0');
INSERT INTO `pm_order_baoguo` VALUES ('26', '8', '23', '10002', '19102415572943', '3', '0.00', '0.00', '1.00', 'EWE', '4', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'Chemist Warehouse', '18700001111', '0', '0', '0', '0', '0', '0', '1571903850', '0');
INSERT INTO `pm_order_baoguo` VALUES ('27', '7', '24', '10002', '19102417274647', '1', '142.50', '0.00', '6.00', '4PX', '5', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571909266', '0');
INSERT INTO `pm_order_baoguo` VALUES ('28', '7', '24', '10002', '19102417274647', '1', '142.50', '0.00', '6.00', '4PX', '5', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571909266', '0');
INSERT INTO `pm_order_baoguo` VALUES ('29', '7', '24', '10002', '19102417274647', '1', '140.00', '0.00', '6.00', '4PX', '5', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571909266', '0');
INSERT INTO `pm_order_baoguo` VALUES ('30', '7', '24', '10002', '19102417274647', '1', '72.50', '0.00', '3.00', '4PX', '5', '', '', '', '张明', '13500000000', '北京市', '北京市', '东城区', '1111111111', 'TerryWhite', '13500000000', '0', '0', '0', '0', '0', '0', '1571909266', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_cart
-- ----------------------------
INSERT INTO `pm_order_cart` VALUES ('1', '10001', '1', '9', '0', '0', 'Eaoron 水光针眼霜 15g', 'http://www.tourbuy.net/uploads/images/20190920/f02c2d8dc1dacafe2794668155e3160c.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('2', '10001', '2', '6', '0', '0', '爱他美金装 二段', 'http://www.tourbuy.net/uploads/images/20190920/1bbca64c5a9bd5dddb23d130132a0cf0.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('3', '10001', '3', '8', '0', '0', 'Eaoron 补水面膜（白）5片/盒', 'http://www.tourbuy.net/uploads/images/20190920/a498288cef351426a62e63f885da0b8c.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('4', '10003', '4', '4', '0', '0', 'A2铂金 四段', 'http://www.tourbuy.net/uploads/images/20190920/2fb79348c9dac48e4c33511cc3a9072e.jpg', '', '39.00', '3', '3');
INSERT INTO `pm_order_cart` VALUES ('5', '10003', '5', '8', '0', '0', 'Eaoron 补水面膜（白）5片/盒', 'http://www.tourbuy.net/uploads/images/20190920/a498288cef351426a62e63f885da0b8c.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('6', '10001', '6', '4', '0', '0', 'A2铂金 四段', 'http://www.tourbuy.net/uploads/images/20190920/2fb79348c9dac48e4c33511cc3a9072e.jpg', '', '39.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('7', '10003', '7', '11', '0', '0', 'Natio 亮白眼霜 20g', 'http://www.tourbuy.net/uploads/images/20190920/ca7d47ee5ab218d5ac6019c6f0a4cc51.jpg', '', '12.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('18', '10002', '18', '7', '0', '0', 'Bio Island 婴幼儿乳钙 奶钙 90粒', 'http://www.tourbuy.net/uploads/images/20190920/3cdceefd115675dc0e9a9e9627af9929.jpg', '', '34.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('9', '10003', '9', '11', '0', '0', 'Natio 亮白眼霜 20g', 'http://www.tourbuy.net/uploads/images/20190920/ca7d47ee5ab218d5ac6019c6f0a4cc51.jpg', '', '0.01', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('10', '10003', '10', '6', '0', '0', '爱他美金装 二段', 'http://www.tourbuy.net/uploads/images/20190920/1bbca64c5a9bd5dddb23d130132a0cf0.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('11', '10002', '11', '9', '0', '0', 'Eaoron 水光针眼霜 15g', 'http://www.tourbuy.net/uploads/images/20190920/f02c2d8dc1dacafe2794668155e3160c.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('12', '10002', '12', '1', '0', '0', 'A2铂金 一段', 'http://www.tourbuy.net/uploads/images/20190920/89c9985bb4d7253b32edea2183e82706.jpg', '', '70.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('13', '10002', '13', '1', '0', '0', 'A2铂金 一段', 'http://www.tourbuy.net/uploads/images/20190920/89c9985bb4d7253b32edea2183e82706.jpg', '', '70.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('14', '10002', '14', '7', '0', '0', 'Bio Island 婴幼儿乳钙 奶钙 90粒', 'http://www.tourbuy.net/uploads/images/20190920/3cdceefd115675dc0e9a9e9627af9929.jpg', '', '34.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('15', '10001', '15', '6', '0', '0', '爱他美金装 二段', 'http://www.tourbuy.net/uploads/images/20190920/1bbca64c5a9bd5dddb23d130132a0cf0.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('16', '10001', '16', '3', '0', '0', 'A2铂金 三段', 'http://www.tourbuy.net/uploads/images/20190920/139476be76497500208c94258527e750.jpg', '', '22.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('19', '10001', '19', '9', '0', '0', 'Eaoron 水光针眼霜 15g', 'http://www.tourbuy.net/uploads/images/20190920/f02c2d8dc1dacafe2794668155e3160c.jpg', '', '18.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('20', '10001', '20', '9', '0', '0', 'Eaoron 水光针眼霜 15g', 'http://www.tourbuy.net/uploads/images/20190920/f02c2d8dc1dacafe2794668155e3160c.jpg', '', '20.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('21', '10002', '22', '6', '0', '0', '化妆品', 'http://www.tourbuy.net/uploads/images/20190920/1bbca64c5a9bd5dddb23d130132a0cf0.jpg', '', '20.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('22', '10002', '22', '7', '0', '0', '1个不混', 'http://www.tourbuy.net/uploads/images/20190920/3cdceefd115675dc0e9a9e9627af9929.jpg', '', '50.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('23', '10002', '22', '5', '0', '0', '日用品/杂货', 'http://www.tourbuy.net/uploads/images/20190920/71e3a4f76aab8f6e6fbe58931a730625.jpg', '', '19.00', '1', '1');
INSERT INTO `pm_order_cart` VALUES ('24', '10002', '23', '1', '0', '0', '罐装奶粉', 'http://www.tourbuy.net/uploads/images/20190920/89c9985bb4d7253b32edea2183e82706.jpg', '', '79.00', '2', '2');
INSERT INTO `pm_order_cart` VALUES ('25', '10002', '23', '2', '0', '0', '袋装奶粉', 'http://www.tourbuy.net/uploads/images/20190920/2d0d6b3816cbfe1acf4c930beb29231d.jpg', '', '37.00', '2', '2');
INSERT INTO `pm_order_cart` VALUES ('26', '10002', '23', '3', '0', '0', '保健品/食品', 'http://www.tourbuy.net/uploads/images/20190920/139476be76497500208c94258527e750.jpg', '', '24.00', '3', '3');
INSERT INTO `pm_order_cart` VALUES ('27', '10002', '24', '12', '0', '0', '红酒1', '2', '', '100.00', '15', '15');
INSERT INTO `pm_order_cart` VALUES ('28', '10002', '24', '13', '0', '0', '红酒一箱', '1', '', '500.00', '1', '6');

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_order_detail
-- ----------------------------
INSERT INTO `pm_order_detail` VALUES ('1', '1', '10001', '1', '9', '0', 'Eaoron 水光针眼霜 15g', 'Eaoron 水光针眼霜 15g', '1', '18.00', '0', '1569986091');
INSERT INTO `pm_order_detail` VALUES ('2', '2', '10001', '2', '6', '0', '爱他美金装 二段', '1', '1', '18.00', '0', '1569986160');
INSERT INTO `pm_order_detail` VALUES ('3', '3', '10001', '3', '8', '0', 'Eaoron 补水面膜（白）5片/盒', '122', '1', '18.00', '0', '1569987421');
INSERT INTO `pm_order_detail` VALUES ('4', '4', '10003', '4', '4', '0', 'A2铂金 四段', '毛呢大衣', '3', '39.00', '0', '1569987533');
INSERT INTO `pm_order_detail` VALUES ('5', '5', '10003', '5', '8', '0', 'Eaoron 补水面膜（白）5片/盒', '122', '1', '18.00', '0', '1569987533');
INSERT INTO `pm_order_detail` VALUES ('6', '6', '10001', '6', '4', '0', 'A2铂金 四段', '毛呢大衣', '1', '39.00', '0', '1569987623');
INSERT INTO `pm_order_detail` VALUES ('7', '7', '10003', '7', '11', '0', 'Natio 亮白眼霜 20g', 'Natio 亮白眼霜 20g', '1', '12.00', '0', '1569987723');
INSERT INTO `pm_order_detail` VALUES ('9', '9', '10003', '9', '11', '0', 'Natio 亮白眼霜 20g', 'Natio 亮白眼霜 20g', '1', '0.01', '0', '1569987827');
INSERT INTO `pm_order_detail` VALUES ('10', '10', '10003', '10', '6', '0', '爱他美金装 二段', '1', '1', '18.00', '0', '1570000285');
INSERT INTO `pm_order_detail` VALUES ('11', '11', '10002', '11', '9', '0', 'Eaoron 水光针眼霜 15g', 'Eaoron 水光针眼霜 15g', '1', '18.00', '0', '1570002194');
INSERT INTO `pm_order_detail` VALUES ('12', '12', '10002', '12', '1', '0', 'A2铂金 一段', '优衣库', '1', '70.00', '0', '1570004461');
INSERT INTO `pm_order_detail` VALUES ('13', '13', '10002', '13', '1', '0', 'A2铂金 一段', '优衣库', '1', '70.00', '0', '1570004522');
INSERT INTO `pm_order_detail` VALUES ('14', '14', '10002', '14', '7', '0', 'Bio Island 婴幼儿乳钙 奶钙 90粒', '12', '1', '34.00', '0', '1570005448');
INSERT INTO `pm_order_detail` VALUES ('15', '15', '10001', '15', '6', '0', '爱他美金装 二段', '1', '1', '18.00', '0', '1570024544');
INSERT INTO `pm_order_detail` VALUES ('16', '16', '10001', '16', '3', '0', 'A2铂金 三段', '文胸', '1', '22.00', '0', '1570062746');
INSERT INTO `pm_order_detail` VALUES ('18', '18', '10002', '18', '7', '0', 'Bio Island 婴幼儿乳钙 奶钙 90粒', '12', '1', '34.00', '0', '1570428662');
INSERT INTO `pm_order_detail` VALUES ('19', '20', '10001', '19', '9', '0', 'Eaoron 水光针眼霜 15g', 'Eaoron 水光针眼霜 15g', '1', '20.00', '0', '1570720975');
INSERT INTO `pm_order_detail` VALUES ('20', '22', '10002', '20', '6', '0', '化妆品', '1', '1', '20.00', '0', '1571903849');
INSERT INTO `pm_order_detail` VALUES ('21', '22', '10002', '20', '5', '0', '日用品/杂货', '贝拉米有机婴儿米粉', '1', '19.00', '0', '1571903849');
INSERT INTO `pm_order_detail` VALUES ('22', '22', '10002', '21', '7', '0', '1个不混', '12', '1', '50.00', '0', '1571903849');
INSERT INTO `pm_order_detail` VALUES ('23', '23', '10002', '22', '1', '0', '罐装奶粉', '优衣库', '2', '79.00', '0', '1571903849');
INSERT INTO `pm_order_detail` VALUES ('24', '23', '10002', '23', '2', '0', '袋装奶粉', '222', '2', '37.00', '0', '1571903849');
INSERT INTO `pm_order_detail` VALUES ('25', '23', '10002', '24', '3', '0', '保健品/食品', '文胸', '1', '24.00', '0', '1571903850');
INSERT INTO `pm_order_detail` VALUES ('26', '23', '10002', '25', '3', '0', '保健品/食品', '文胸', '1', '24.00', '0', '1571903850');
INSERT INTO `pm_order_detail` VALUES ('27', '23', '10002', '26', '3', '0', '保健品/食品', '文胸', '1', '24.00', '0', '1571903850');
INSERT INTO `pm_order_detail` VALUES ('28', '24', '10002', '27', '12', '0', '红酒1', '11', '6', '100.00', '0', '1571909266');
INSERT INTO `pm_order_detail` VALUES ('29', '24', '10002', '28', '12', '0', '红酒1', '11', '6', '100.00', '0', '1571909266');
INSERT INTO `pm_order_detail` VALUES ('30', '24', '10002', '29', '12', '0', '红酒1', '11', '3', '100.00', '0', '1571909266');
INSERT INTO `pm_order_detail` VALUES ('31', '24', '10002', '29', '13', '0', '红酒一箱', '1', '3', '500.00', '0', '1571909266');
INSERT INTO `pm_order_detail` VALUES ('32', '24', '10002', '30', '13', '0', '红酒一箱', '1', '3', '500.00', '0', '1571909266');

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
  `cid` int(11) NOT NULL,
  `cate` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `picname` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `linkman` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `intr` varchar(200) NOT NULL,
  `mp4` varchar(300) DEFAULT NULL,
  `image` text NOT NULL,
  `openTime` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `masterTel` varchar(200) NOT NULL,
  `masterEmail` varchar(200) NOT NULL,
  `cbn` varchar(100) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `bankAccount` varchar(100) NOT NULL,
  `bsb` varchar(100) NOT NULL,
  `bankNumber` varchar(100) NOT NULL,
  `py` varchar(10) NOT NULL,
  `group` tinyint(4) NOT NULL COMMENT '0普通商户1特惠商家',
  `comm` int(11) NOT NULL COMMENT '推荐栏目ID',
  `submit` tinyint(4) NOT NULL,
  `edit` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_shop
-- ----------------------------
INSERT INTO `pm_shop` VALUES ('7', '1', '25', '4,7,3', 'TerryWhite', 'test', '64def183c8846acf3f9e13799e627a17', '/uploads/images/20190920/4b3024f997cad54066b19b2f1fa3cbbe.jpg', '/uploads/images/20190920/95b938fbda6c359a06e29e40727d65eb.jpg', 'jack', '阿德莱德长安大街11号', '13500000000', '', '一家很不错的商店', 'admin', '/uploads/images/20190822/292fc46c8c0fe690c4b7f4acbaf56fed.jpg,/uploads/images/20190822/86708d2a1662fa3ec163fbc1ab34af6d.jpg', '8点到9点周一到周五', '这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好\n嗯嗯这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好\n这个店铺很好很好很好这个店铺很好很好很好这个店铺很好很好很好', '', '', '', '', '', '', '', 'T', '0', '4', '1', '1', '1', '1566488164', '1569986450');
INSERT INTO `pm_shop` VALUES ('8', '2', '25', '4,7,3', 'Chemist Warehouse', 'test1', '64def183c8846acf3f9e13799e627a17', '/uploads/images/20190920/17bb911ce1096649a1b1cbe8d79d3f65.jpg', '/uploads/images/20190920/91baba1ed1f301f6a325451042534272.jpg', '赵柳', '阿萨德饭大是大非ad', '18700001111', '', '我们只卖一种保健品', 'admin', '', '', '我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片我是店铺介绍店铺介绍片', '', '', '', '', '', '', '', 'C', '0', '4', '1', '1', '1', '1567475240', '1569986438');
INSERT INTO `pm_shop` VALUES ('9', '1', '26', '7', '水光针专卖', '测试', 'e10adc3949ba59abbe56e057f20f883e', '/uploads/images/20190920/65fa668199432d806fb39d5e64317a01.png', '/uploads/images/20190920/06a4845b28fcec25b05d5eeb90373118.png', 'Shanguang Ping', '18B Packer Crescent', '0410867533', '', '我只卖水光针', '', '', '周一至周五的营业时间一般是从早上9点或10点到下午5点或5:30。每周可能有一次晚间营业，通常为周四。商店营业时间通常较长 — 常常营业到晚上7点，而且每周7天均是如此。', '别看那么多，买我就对了', '', '', '', '', '', '', '', 'S', '0', '7', '1', '1', '1', '1568957615', '1569989847');
INSERT INTO `pm_shop` VALUES ('10', '2', '26', '7', 'Natio 系列专卖', '测试02', '64def183c8846acf3f9e13799e627a17', '/uploads/images/20190920/46dac460fed6742eb847aed187df8568.jpg', '/uploads/images/20190920/770fa057682149aec7f391f533df5851.jpg', 'Shanguang Ping', '18B Packer Crescent', '0410867533', '', '我只卖Natio系列', 'admin', '', '周一至周五的营业时间一般是从早上9点或10点到下午5点或5:30。\n每周可能有一次晚间营业，通常为周四。\n商店营业时间通常较长 — 常常营业到晚上7点，而且每周7天均是如此。', '别看那么多，买我就对了', '', '', '', '', '', '', '', 'N', '0', '7', '0', '0', '1', '1568962558', '1568962909');
INSERT INTO `pm_shop` VALUES ('11', '1', '27', '4,7,3,1,2,5,6,8,9,30', '单独红酒测试店铺', 'test007', '8c1c89cf22495f60e13bbbdb0717fed2', '', '', 'ERIC YAO', '20 BOTANIC GROVE, campbelltown', '0430615511', '8019939@gmail.com', '单独店铺测试帐号', 'https://www.youtube.com/watch?v=9HgPlOjo6Zw&t=1s', '/uploads/images/20191007/008128b9a475ffb7745f8e84cdfef976.jpg', '10-17', '', '0430615511', '8019939@gmail.com', '', 'cba', 'test', '065000', '135456', 'D', '0', '5', '1', '1', '1', '1570429445', '1570429445');
INSERT INTO `pm_shop` VALUES ('12', '3', '27', '4,7,3,5,9', '昆士兰旗舰店', 'sumaking', '8cb03ec43337226d181cfa0981012c79', '/uploads/images/20191009/b5ea20e09e3e5de86c93f720ed00f2e8.png', '/uploads/images/20191007/fd87e3c828c5f3d998ecf01690161345.jpg', 'Mikel', 'Carina Heights ', '0479123435', 'maimengstudio@outlook.com', '主打昆士兰州特色产品', '', '/uploads/images/20191007/8a48ae6b5b0a0eed8adf268a5ce7147c.jpeg', '6:00-22:00', '昆州最专业保健品店，给您最地道的昆州购物体验', '0479123435', 'maimengstudio@outlook.com', '51919767096', 'nab', 'Xiaomeng Zhao', '084130', '156727847', 'K', '0', '3', '1', '1', '1', '1570447898', '1570620613');

-- ----------------------------
-- Table structure for `pm_shop_cate`
-- ----------------------------
DROP TABLE IF EXISTS `pm_shop_cate`;
CREATE TABLE `pm_shop_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) DEFAULT NULL,
  `cateID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_shop_cate
-- ----------------------------
INSERT INTO `pm_shop_cate` VALUES ('69', '8', '3');
INSERT INTO `pm_shop_cate` VALUES ('72', '7', '3');
INSERT INTO `pm_shop_cate` VALUES ('73', '9', '7');
INSERT INTO `pm_shop_cate` VALUES ('71', '7', '7');
INSERT INTO `pm_shop_cate` VALUES ('70', '7', '4');
INSERT INTO `pm_shop_cate` VALUES ('68', '8', '7');
INSERT INTO `pm_shop_cate` VALUES ('67', '8', '4');
INSERT INTO `pm_shop_cate` VALUES ('62', '10', '7');
INSERT INTO `pm_shop_cate` VALUES ('74', '11', '4');
INSERT INTO `pm_shop_cate` VALUES ('75', '11', '7');
INSERT INTO `pm_shop_cate` VALUES ('76', '11', '3');
INSERT INTO `pm_shop_cate` VALUES ('77', '11', '1');
INSERT INTO `pm_shop_cate` VALUES ('78', '11', '2');
INSERT INTO `pm_shop_cate` VALUES ('79', '11', '5');
INSERT INTO `pm_shop_cate` VALUES ('80', '11', '6');
INSERT INTO `pm_shop_cate` VALUES ('81', '11', '8');
INSERT INTO `pm_shop_cate` VALUES ('82', '11', '9');
INSERT INTO `pm_shop_cate` VALUES ('83', '11', '30');
INSERT INTO `pm_shop_cate` VALUES ('93', '12', '9');
INSERT INTO `pm_shop_cate` VALUES ('92', '12', '5');
INSERT INTO `pm_shop_cate` VALUES ('91', '12', '3');
INSERT INTO `pm_shop_cate` VALUES ('90', '12', '7');
INSERT INTO `pm_shop_cate` VALUES ('89', '12', '4');

-- ----------------------------
-- Table structure for `pm_shop_comment`
-- ----------------------------
DROP TABLE IF EXISTS `pm_shop_comment`;
CREATE TABLE `pm_shop_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  `headimg` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pm_shop_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_shop_fav`
-- ----------------------------
DROP TABLE IF EXISTS `pm_shop_fav`;
CREATE TABLE `pm_shop_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

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
INSERT INTO `pm_shop_fav` VALUES ('20', '11', '10002');

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
