#!/usr/bin/env ruby
require 'rubygems'
require 'json'
require 'optparse'
require 'net/http'

### Server and Token Information
server=''
port=''
endpoint=''
token=''

#############################################
options = {}
action = ""
oparse = OptionParser.new do |opt|
  opt.banner = "Usage: eat [OPTIONS] ITEM"
  opt.separator ""
  opt.separator "Options"
  opt.on("-a","--add", "Add a food item to the list") do
    action = "add"
  end
  opt.on("-c","--clear", "Clear the food list") do
    action = "clear"
  end
  opt.on("-h","--help","help") do
    puts oparse
  end
end

oparse.parse!

bundle = { "Token" => token, "Action" => action, "Content" => ARGV.join(" ") }

if action == ""
    puts oparse
    exit 1
end

if ARGV.join(" ").length > 128
    puts "Message too long!"
    exit 1
end

net = Net::HTTP.new(server, port)
request = Net::HTTP::Post.new(endpoint)
request.set_form_data({"foodaction" => bundle.to_json})
response = net.start do |http|
    http.request(request)
end


if response.code == "200"
    if response.read_body == "ERRSIZE"
        puts "Message too long."
    else
        puts "Success! Take a look at http://" + server + ":" + port + endpoint
    end
else
    puts "Something happened. Here's what the server says:"
    puts response.read_body
end
