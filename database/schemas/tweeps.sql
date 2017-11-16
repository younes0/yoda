/*
Navicat PGSQL Data Transfer

Source Server         : Local Postgres
Source Server Version : 90404
Source Host           : 192.168.56.20:5432
Source Database       : yoda
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90404
File Encoding         : 65001

Date: 2015-09-08 10:55:57
*/


-- ----------------------------
-- Table structure for tweeps
-- ----------------------------
DROP TABLE IF EXISTS "public"."tweeps";
CREATE TABLE "public"."tweeps" (
"id" varchar COLLATE "default" NOT NULL,
"is_human_approved" bool,
"score" int4,
"tweets_per_day" numeric,
"links_per_tweet" numeric,
"created_at" timestamp(6),
"updated_at" timestamp(6),
"metrics_updated_at" timestamp(6),
"proper_lang_per_tweet" numeric,
"is_machine_approved" bool
)
WITH (OIDS=FALSE)

;