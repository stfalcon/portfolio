set :application, "portfolio of stfalcon-studio"
set :domain,      "stfalcon.com"
set :deploy_to,   "~/stfalcon.com"
set :app_path,    "app"

set :repository,  "git://github.com/stfalcon/portfolio.git"
set :scm,         :git
set :git_enable_submodules, 1

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set  :keep_releases,  3
set  :user,       "jeka"
set  :use_sudo,   false

default_run_options[:pty] = true 
set :update_vendors, true

set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]
set :dump_assetic_assets, true
set :deploy_via, :rsync_with_remote_cache
