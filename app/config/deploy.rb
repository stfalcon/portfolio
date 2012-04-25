set :application, "stfalcon"
set :domain,      "#{application}.com"
set :deploy_to,   "/var/www/#{domain}"
set :app_path,    "app"

set :repository,  "git://github.com/stfalcon/portfolio.git"
set :scm,         :git
set :git_enable_submodules, 1

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

default_run_options[:pty] = true 

set :keep_releases, 3
set :user,          "stfalcon-com"
set :use_sudo,      false

set :use_composer,  true
#set :deploy_via,    :rsync_with_remote_cache

set :shared_files,        ["app/config/parameters.ini"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", web_path + "/bundles", "vendor"]
set :dump_assetic_assets, true
