load 'deploy' if respond_to?(:namespace) # cap2 differentiator
Dir['vendor/bundles/*/*/recipes/*.rb'].each { |bundle| load(bundle) }
load Gem.find_files('symfony2.rb').last.to_s
load 'app/config/deploy'