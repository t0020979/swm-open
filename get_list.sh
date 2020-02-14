#!/bin/sh

# cd ~/Work/s1/seowork-main


# Working - COPY FOLDERS
git clean -dn | cut -c14- | xargs -I % cp -r --parents % ~/Work/github/swm-open/swm

# ?????? - COPY FILES
git diff master --name-only | xargs -I % cp -r --parents % ~/Work/github/swm-open/swm


# Create File-changes list for Task

# git clean -dn | cut -c14- > ~/Work/github/swm-open/lists/SEO-12229.dir.list
# git clean -dn | cut -c14- | xargs -i -- echo ls -1 {}\; ls -1F {} | bash  > ~/Work/github/swm-open/lists/SEO-12229.file.list


# git clean -dn | cut -c14- | xargs -I % ls -1F %
