# $Id: Makefile,v 1.7 2004-06-20 04:32:26 bfulgham Exp $

include Make.header

all: versions links-stamp
	(cd bench ; make $@)
	(cd bench ; make --no-print-directory report> report.txt)
	make recent craps codelinks

test plot show loc:
	(cd bench ; make $@)

clean:
	(cd bench ; make $@)

.PHONY: dist report recent versions craps codelinks

versions: versions.html

versions.html: $(LANGS) bin/make_versions langs.pl
	bin/make_versions > versions.html.tmp && \
	mv -f versions.html.tmp versions.html

dist:
	bin/make_dist

report codelinks:
	@(cd bench ; make --no-print-directory $@)

craps:
	@(cd bench ; make --no-print-directory $@) > .craps.table

recent:
	@bin/make_recent > recent.html

links:  links-stamp
links-stamp:
	# All Java's use the same source
	for j in `find bench -name '*.java'`; do \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.java'`.kaffe; \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.java'`.gcj; \
	done

	# Hipe and Erlang are the same
	for j in `find bench -name '*.erlang'`; do \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.erlang'`.hipe; \
	done

	# Ocaml and Ocamlb are the same
	for j in `find bench -name '*.ocaml'`; do \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.ocaml'`.ocamlb; \
	done

	# MzScheme and Mzc are the same
	for j in `find bench -name '*.mzscheme'`; do \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.mzscheme'`.mzc; \
	done

	# CMUCL and SBCL are the same
	for j in `find bench -name '*.cmucl'`; do \
		ln -sf `basename $$j` `expr $$j : '\(.*\)\.cmucl'`.sbcl; \
	done

	touch links-stamp
