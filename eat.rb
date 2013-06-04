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
proxy = ENV['http_proxy'] ? URI.parse(ENV['http_proxy']) : OpenStruct.new

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
  opt.on("-t","--test", "Test the connection") do
    action = "test"
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

net = Net::HTTP::Proxy(proxy.host,proxy.port,proxy.user,proxy.password).new(server, port)
request = Net::HTTP::Post.new(endpoint)
request.set_form_data({"foodaction" => bundle.to_json})
response = net.start do |http|
    http.request(request)
end


if response.code == "200"
    if response.read_body == "ERR_CONTENT_TOO_BIG"
        puts "Message too long."
        exit 1
    elsif response.read_body == "ERR_WRONG_TOKEN"
        puts "Your token is incorrect"
        exit 1
    elsif response.read_body == "ERR_DB"
        puts "The entry couldnt be written to the DB."
        exit 1
    elsif response.read_body == "OK_TEST"
        puts "Test success."
        exit
    elsif response.read_body == "OK"
        puts "Success! Take a look at http://" + server + ":" + port + endpoint
        exit
    else
        puts "Something really weird happened. Here's the output:"
        puts response.read_body
    end
else
    puts "I didn't even get a HTTP 200. Here's what I got:"
    puts response.read_body
end
